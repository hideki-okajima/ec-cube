<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eccube\Controller\Admin;

use Eccube\Controller\AbstractController;
use Eccube\Entity\Member;
use Eccube\Entity\OAuth2\Client;
use Eccube\Entity\OAuth2\ClientScope;
use Eccube\Entity\OAuth2\OpenID\PublicKey;
use Eccube\Entity\OAuth2\OpenID\UserInfo;
use Eccube\Entity\OAuth2\OpenID\UserInfoAddress;
use Eccube\Entity\OAuth2\Scope;
use Eccube\Form\Type\OAuth2\ApiClientType;
use Eccube\Repository\MemberRepository;
use Eccube\Repository\OAuth2\ClientRepository;
use Eccube\Repository\OAuth2\ClientScopeRepository;
use Eccube\Repository\OAuth2\OpenID\PublicKeyRepository;
use Eccube\Repository\OAuth2\OpenID\UserInfoRepository;
use Eccube\Repository\OAuth2\ScopeRepository;
use phpseclib\Crypt\RSA;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * ApiClientController
 *
 * APIクライアントを管理するためのコントローラ
 */
class ApiClientController extends AbstractController
{
    /** デフォルトの暗号化方式. */
    const DEFAULT_ENCRYPTION_ALGORITHM = 'RS256';

    /**
     * @var MemberRepository
     */
    private $memberRepository;
    /**
     * @var ClientRepository
     */
    private $clientRepository;
    /**
     * @var ScopeRepository
     */
    private $scopeRepository;
    /**
     * @var UserInfoRepository
     */
    private $userInfoRepository;
    /**
     * @var PublicKeyRepository
     */
    private $publicKeyRepository;
    /**
     * @var ClientScopeRepository
     */
    private $clientScopeRepository;

    /**
     * ApiClientController constructor.
     *
     * @param MemberRepository $memberRepository
     * @param ClientRepository $clientRepository
     * @param ScopeRepository $scopeRepository
     * @param UserInfoRepository $userInfoRepository
     * @param PublicKeyRepository $publicKeyRepository
     * @param ClientScopeRepository $clientScopeRepository
     */
    public function __construct(
        MemberRepository $memberRepository,
        ClientRepository $clientRepository,
        ScopeRepository $scopeRepository,
        UserInfoRepository $userInfoRepository,
        PublicKeyRepository $publicKeyRepository,
        ClientScopeRepository $clientScopeRepository)
    {
        $this->memberRepository = $memberRepository;
        $this->clientRepository = $clientRepository;
        $this->scopeRepository = $scopeRepository;
        $this->userInfoRepository = $userInfoRepository;
        $this->publicKeyRepository = $publicKeyRepository;
        $this->clientScopeRepository = $clientScopeRepository;
    }

    /**
     * API クライアント一覧を表示します.
     *
     * @param integer $member_id \Eccube\Entity\Member の ID
     *
     * @return array
     *
     * @Route("/%eccube_admin_route%/setting/system/member/{member_id}/api", name="admin_api_lists")
     * @Template("@admin/Api/lists.twig")
     */
    public function lists($member_id = null)
    {
        // ログイン中のユーザーのインスタンスによって処理を切り替える
        $User = $this->memberRepository->find($member_id);
        $Clients = $this->clientRepository->findBy(['Member' => $User]);

        $builder = $this->formFactory->createBuilder();
        $form = $builder->getForm();

        return [
            'form' => $form->createView(),
            'User' => $User,
            'Clients' => $Clients,
        ];
    }

    /**
     * APIクライアントを編集します.
     *
     * @param Request $request
     * @param integer $member_id \Eccube\Entity\Member の ID
     * @param integer $client_id APIクライアントID
     *
     * @return array|RedirectResponse
     *
     * @Route("/%eccube_admin_route%/setting/system/member/{member_id}/api/{client_id}/edit", name="admin_setting_system_client_edit")
     * @Template("@admin/Api/edit.twig")
     */
    public function edit(Request $request, $member_id = null, $client_id = null)
    {
        $Member = $this->memberRepository->find($member_id);

        // Client が保持する Scope の配列を取得する
        $Client = $this->clientRepository->find($client_id);
        $Scopes = array_map(function ($ClientScope) {
            return $ClientScope->getScope();
        }, $this->clientScopeRepository->findBy(['Client' => $Client]));

        $UserInfo = $this->userInfoRepository->findOneBy(['Member' => $Client->getMember()]);
        $PublicKey = $this->publicKeyRepository->findOneBy(['UserInfo' => $UserInfo]);

        $builder = $this->formFactory->createBuilder(ApiClientType::class, $Client);

        $form = $builder->getForm();

        $form['Scopes']->setData($Scopes);

        if ($PublicKey) {
            $form['public_key']->setData($PublicKey->getPublicKey());
            $form['encryption_algorithm']->setData($PublicKey->getEncryptionAlgorithm());
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ClientScopes = $this->clientScopeRepository->findBy(['Client' => $Client]);
            foreach ($ClientScopes as $ClientScope) {
                $this->entityManager->remove($ClientScope);
                $this->entityManager->flush($ClientScope);
            }

            $Scopes = $form['Scopes']->getData();
            foreach ($Scopes as $Scope) {
                $ClientScope = new ClientScope();
                $ClientScope->setClient($Client);
                $ClientScope->setClientId($Client->getId());
                $ClientScope->setScope($Scope);
                $ClientScope->setScopeId($Scope->getId());
                $this->entityManager->persist($ClientScope);
                $Client->addClientScope($ClientScope);
            }

            $this->entityManager->flush($Client);
            $this->addSuccess('admin.register.complete', 'admin');
            $route = 'admin_setting_system_client_edit';

            return $this->redirectToRoute($route, ['member_id' => $member_id, 'client_id' => $client_id]);
        }

        return [
            'form' => $form->createView(),
            'User' => $Member,
            'Client' => $Client,
        ];
    }

    /**
     * APIクライアントを新規作成する.
     *
     * @param Request $request
     * @param integer $member_id \Eccube\Entity\Member の ID
     *
     * @return array|RedirectResponse
     *
     * @throws \JOSE_Exception_UnexpectedAlgorithm
     *
     * @Route("/%eccube_admin_route%/setting/system/member/{member_id}/api/new", name="admin_setting_system_client_new")
     * @Template("@admin/Api/edit.twig")
     */
    public function newClient(Request $request, $member_id = null)
    {
        $Member = $this->memberRepository->find($member_id);
        $Client = new Client();

        $builder = $this->formFactory->createBuilder(ApiClientType::class, $Client);

        $form = $builder->getForm();

        $form['encryption_algorithm']->setData(self::DEFAULT_ENCRYPTION_ALGORITHM);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $PublicKey = null;
            $UserInfo = $this->userInfoRepository->findOneBy(['Member' => $Member]);
            if (!is_object($UserInfo)) {
                // 該当ユーザーの UserInfo が存在しない場合は生成する
                $UserInfo = new UserInfo();
                $UserInfo->setAddress(new UserInfoAddress());
            } else {
                $PublicKey = $this->publicKeyRepository->findOneBy(['UserInfo' => $UserInfo]);
            }

            $client_id = sha1(openssl_random_pseudo_bytes(100));
            $client_secret = sha1(openssl_random_pseudo_bytes(100));

            $Client->setClientIdentifier($client_id);
            $Client->setClientSecret($client_secret);

            $Client->setMember($Member);

            $this->entityManager->persist($Client);
            $this->entityManager->flush($Client);

            $Scopes = $form['Scopes']->getData();
            foreach ($Scopes as $Scope) {
                $ClientScope = new ClientScope();
                $ClientScope->setClient($Client);
                $ClientScope->setClientId($Client->getId());
                $ClientScope->setScope($Scope);
                $ClientScope->setScopeId($Scope->getId());
                $this->entityManager->persist($ClientScope);
                $Client->addClientScope($ClientScope);
            }

            $is_new_public_key = false;
            if (!is_object($PublicKey)) {
                // 該当ユーザーの公開鍵が存在しない場合は生成する. UserInfo と 公開鍵は 1:1 となる.
                $RSAKey = new RSA();
                $is_new_public_key = true;
                $keys = $RSAKey->createKey(2048);
                $PublicKey = new PublicKey();
                $PublicKey->setPublicKey($keys['publickey']);
                $PublicKey->setPrivateKey($keys['privatekey']);
                $PublicKey->setEncryptionAlgorithm($form['encryption_algorithm']->getData());
                $PublicKey->setUserInfo($UserInfo);

                $RSAKey->loadKey($keys['publickey']);
                $JWK = \JOSE_JWK::encode($RSAKey);
                // 公開鍵の指紋を UserInfo::sub に設定する. Self-Issued ID Token Validation に準拠した形式
                // http://openid-foundation-japan.github.io/openid-connect-core-1_0.ja.html#SelfIssuedValidation
                $UserInfo->setSub($JWK->thumbprint());
            }

            $UserInfo->setMember($Member);
            $UserInfo->mergeMember();

            if (is_object($UserInfo->getAddress())
                && is_null($UserInfo->getAddress()->getId())) {
                $UserInfoAddress = $UserInfo->getAddress();
                $this->entityManager->persist($UserInfoAddress);
                $this->entityManager->flush($UserInfoAddress);
                $UserInfo->setAddress($UserInfoAddress);
            }
            $this->entityManager->persist($UserInfo);
            if ($is_new_public_key) {
                $this->entityManager->persist($PublicKey);
            }

            $this->entityManager->flush();

            $this->addSuccess('admin.register.complete', 'admin');
            $route = 'admin_setting_system_client_edit';

            return $this->redirectToRoute($route, ['member_id' => $member_id, 'client_id' => $Client->getId()]);
        }

        return [
            'form' => $form->createView(),
            'User' => $Member,
            'Client' => $Client,
        ];
    }

    /**
     * APIクライアントを削除する.
     *
     * @param Request $request
     * @param integer $member_id \Eccube\Entity\Member の ID
     * @param integer $client_id APIクライアントID
     *
     * @return array|RedirectResponse
     *
     * @Route("/%eccube_admin_route%/setting/system/member/{member_id}/api/{client_id}/delete", name="admin_setting_system_client_delete")
     */
    public function delete(Request $request, $member_id = null, $client_id = null)
    {
//        $this->isTokenValid($app);
//
//        $Client = $app['eccube.repository.oauth2.client']->find($client_id);
//
//        $ClientScopes = $app['eccube.repository.oauth2.clientscope']->findBy(['client_id' => $Client->getId()]);
//        foreach ($ClientScopes as $ClientScope) {
//            $app['orm.em']->remove($ClientScope);
//            $app['orm.em']->flush($ClientScope);
//        }
//        $RefreshTokens = $app['eccube.repository.oauth2.refresh_token']->findBy(['client_id' => $Client->getId()]);
//        foreach ($RefreshTokens as $RefreshToken) {
//            $app['orm.em']->remove($RefreshToken);
//            $app['orm.em']->flush($RefreshToken);
//        }
//        $AuthorizationCodes = $app['eccube.repository.oauth2.authorization_code']->findBy(['client_id' => $Client->getId()]);
//        foreach ($AuthorizationCodes as $AuthorizationCode) {
//            $app['orm.em']->remove($AuthorizationCode);
//            $app['orm.em']->flush($AuthorizationCode);
//        }
//        $AccessTokens = $app['eccube.repository.oauth2.access_token']->findBy(['client_id' => $Client->getId()]);
//        foreach ($AccessTokens as $AccessToken) {
//            $app['orm.em']->remove($AccessToken);
//            $app['orm.em']->flush($AccessToken);
//        }
//        $app['orm.em']->remove($Client);
//        $app['orm.em']->flush($Client);
//
//        if ($app->user() instanceof Member) {
//            $route = 'admin_api_lists';
//        } else {
//            $route = 'mypage_api_lists';
//        }
//
        return $this->redirectToRoute('mypage_api_lists', ['member_id' => $member_id]);
    }
}
