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

namespace Eccube\Controller\OAuth2;

use Eccube\Controller\AbstractController;
use Eccube\Entity\OAuth2\Client;
use Eccube\Form\Type\OAuth2\OAuth2AuthorizationType;
use Eccube\Repository\OAuth2\ClientRepository;
use Eccube\Repository\OAuth2\OpenID\UserInfoRepository;
use Eccube\Repository\OAuth2\ScopeRepository;
use OAuth2\HttpFoundationBridge\Request as BridgeRequest;
use OAuth2\ResponseInterface;
use OAuth2\Server;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use OAuth2\HttpFoundationBridge\Response as BridgeResponse;
use OAuth2\Encryption\FirebaseJwt as Jwt;
use Symfony\Component\Routing\Annotation\Route;

/**
 * OAuth2.0 Authorization をするためのコントローラ.
 */
class OAuth2Controller extends AbstractController
{
    /**
     * @var ClientRepository
     */
    private $clientRepository;
    /**
     * @var UserInfoRepository
     */
    private $userInfoRepository;
    /**
     * @var ScopeRepository
     */
    private $scopeRepository;

    /**
     * OAuth2Controller constructor.
     *
     * @param ClientRepository $clientRepository
     * @param UserInfoRepository $userInfoRepository
     * @param ScopeRepository $scopeRepository
     */
    public function __construct(ClientRepository $clientRepository, UserInfoRepository $userInfoRepository, ScopeRepository $scopeRepository)
    {
        $this->clientRepository = $clientRepository;
        $this->userInfoRepository = $userInfoRepository;
        $this->scopeRepository = $scopeRepository;
    }

    /**
     * Authorization Endpoint.
     *
     * @param Request $request
     * @param Server $server
     *
     * @return array|ResponseInterface|RedirectResponse
     *
     * @Route("/%eccube_admin_route%/OAuth2/authorize", name="admin_oauth2_server_authorize")
     * @Template("@admin/OAuth2/authorization.twig")
     *
     * @see http://bshaffer.github.io/oauth2-server-php-docs/grant-types/authorization-code/
     */
    public function authorize(Request $request, Server $server)
    {
        // TODO validation
        $client_id = $request->get('client_id');
        $redirect_uri = $request->get('redirect_uri');
        $response_type = $request->get('response_type');
        $state = $request->get('state');
        $scope = $request->get('scope');
        $nonce = $request->get('nonce');
        $is_authorized = (bool) $request->get('authorized');

        $BridgeRequest = $this->createFromRequestWrapper($request);
        $Response = new BridgeResponse();
        $form = $this->formFactory->createNamed(
            '',                 // 無名のフォームを生成
            OAuth2AuthorizationType::class,
            $BridgeRequest->query->all(),
            [// 'csrf_protection' => false,
            ]
        );

        // 認可要求
        if ('POST' === $request->getMethod() && $is_authorized) {
            $form->handleRequest($request);
            // 認可要求の妥当性をチェックする(主にURLパラメータ)
            if (!$server->validateAuthorizeRequest($BridgeRequest, $Response)) {
                return $Response;
            }

            // ログイン中のユーザーと、認可要求された client_id の妥当性をチェックする.
            // CSRFチェック, Client が使用可能な scope のチェック, ログイン中ユーザーの妥当性チェック
            /** @var Client $Client */
            $Client = $this->clientRepository->findOneBy(['client_identifier' => $client_id]);
            if ($form->isValid() && $Client->hasMember() && $this->isGranted('ROLE_ADMIN')) {
                $Member = $Client->getMember();
                if ($Member->getId() !== $this->getUser()->getId()) {
                    $is_authorized = false;
                }
                $UserInfo = $this->userInfoRepository->findOneBy(['Member' => $Member]);
            } else {
                // user unknown
                return $server->handleAuthorizeRequest($BridgeRequest, $Response, false);
            }

            $user_id = null;
            if ($UserInfo) {
                $user_id = $UserInfo->getSub();
            }

            // handle the request
            // TODO $is_authorized == false の場合のエラーメッセージを分けたい
            $Response = $server->handleAuthorizeRequest($BridgeRequest, $Response, $is_authorized, $user_id);
            $content = json_decode($Response->getContent(), true);
            // redirect_uri に urn:ietf:wg:oauth:2.0:oob が指定されていた場合(ネイティブアプリ等)の処理
            if ($BridgeRequest->get('redirect_uri') == 'urn:ietf:wg:oauth:2.0:oob' && empty($content)) {
                $ResponseType = $server->getResponseType('code');
                $res = $ResponseType->getAuthorizeResponse(
                    [
                        'redirect_uri' => 'urn:ietf:wg:oauth:2.0:oob',
                        'client_id' => $client_id,
                        'state' => $state,
                        'scope' => $scope,
                    ],
                    $user_id);

                return $this->redirectToRoute('admin_oauth2_server_authorize_oob', ['code' => $res[1]['query']['code']]);
            }

            return $Response;
        }

        $scopes = [];
        if (!is_null($scope)) {
            $scopes = explode(' ', $scope);
        }

        // 認可リクエスト用の画面を表示
        $Scopes = $this->scopeRepository->findByString($scope, $this->getUser());

        return [
            'client_id' => $client_id,
            'redirect_uri' => $redirect_uri,
            'response_type' => $response_type,
            'state' => $state,
            'scope' => $scope,
            'scopeAsJson' => json_encode($scopes),
            'nonce' => $nonce,
            'form' => $form->createView(),
            'Scopes' => $Scopes,
            ];
    }

//    /**
//     * Authorization code を画面に表示する.
//     *
//     * request_uri に urn:ietf:wg:oauth:2.0:oob が指定された場合はこの画面を表示する.
//     *
//     * @param Application $app
//     * @param Request $request
//     * @param string $code Authorization code の文字列
//     *
//     * @return BridgeResponse
//     */
//    public function authorizeOob(Application $app, Request $request, $code = null)
//    {
//        if ($code === null) {
//            throw new NotFoundHttpException();
//        }
//        $AuthorizationCode = $app['eccube.repository.oauth2.authorization_code']->findOneBy(['code' => $code]);
//        if (!is_object($AuthorizationCode)) {
//            throw new NotFoundHttpException();
//        }
//
//        $view = 'EccubeApi/Resource/template/mypage/OAuth2/authorization_code.twig';
//        if ($app->user() instanceof \Eccube\Entity\Member) {
//            $view = 'EccubeApi/Resource/template/admin/OAuth2/authorization_code.twig';
//        }
//
//        return $app->render(
//            $view,
//            ['code' => $code]
//        );
//    }
//
//    /**
//     * Token Endpoint.
//     *
//     * @param Application $app
//     * @param Request $request
//     *
//     * @return BridgeResponse
//     */
//    public function token(Application $app, Request $request)
//    {
//        return $app['oauth2.server.token']->handleTokenRequest($this->createFromRequestWrapper($request),
//            new BridgeResponse());
//    }
//
//    /**
//     * Tokeninfo Endpoint.
//     *
//     * id_token の妥当性検証のために使用する.
//     *
//     * @param Application $app
//     * @param Request $request
//     *
//     * @return BridgeResponse
//     *
//     * @see https://developers.google.com/identity/protocols/OpenIDConnect#validatinganidtoken
//     */
//    public function tokenInfo(Application $app, Request $request)
//    {
//        // TODO validation
//        $id_token = $request->get('id_token');
//        $AuthorizationCode = $app['eccube.repository.oauth2.authorization_code']->findOneBy(['id_token' => $id_token]);
//        $ErrorResponse = $app->json(
//            [
//                'error' => 'invalid_token',
//                'error_description' => 'Invalid Value',
//            ], 400);
//
//        if (!$AuthorizationCode) {
//            return $ErrorResponse;
//        }
//
//        $Client = $AuthorizationCode->getClient();
//        $public_key = $app['eccube.repository.oauth2.openid.public_key']->getPublicKeyByClientId($Client->getId());
//        $jwt = new Jwt();
//        $payload = $jwt->decode($id_token, $public_key);
//        if (!$payload) {
//            return $ErrorResponse;
//        }
//
//        return $app->json($payload, 200);
//    }
//
//    /**
//     * UserInfo Endpoint.
//     *
//     * このエンドポイントは scope=openid による認可リクエストが必要です.
//     *
//     * @param Application $app
//     * @param Request $request
//     *
//     * @return BridgeResponse
//     */
//    public function userInfo(Application $app, Request $request)
//    {
//        return $app['oauth2.server.resource']->handleUserInfoRequest($this->createFromRequestWrapper($request),
//            new BridgeResponse());
//    }

    /**
     * \OAuth2\HttpFoundationBridge\Request に Authorization ヘッダを付与します.
     *
     * Apache モジュール版の PHP で Authorization ヘッダが無視されてしまうのを回避するラッパーです.
     *
     * @see \OAuth2\HttpFoundationBridge\Request::createFromRequest()
     * @see https://github.com/EC-CUBE/eccube-api/issues/41
     * @see https://github.com/bshaffer/oauth2-server-php/issues/433
     */
    protected function createFromRequestWrapper(Request $request)
    {
        $BridgeRequest = BridgeRequest::createFromRequest($request);
        // XXX https://github.com/EC-CUBE/eccube-api/issues/41
        if (!$BridgeRequest->headers->has('Authorization') && function_exists('apache_request_headers')) {
            $all = apache_request_headers();
            if (array_key_exists('Authorization', $all) && isset($all['Authorization'])) {
                $BridgeRequest->headers->set('Authorization', $all['Authorization']);
            } elseif (array_key_exists('authorization', $all) && isset($all['authorization'])) {
                // ubuntu + Apache 2.4.x の環境で、キーが小文字になっている場合がある
                $BridgeRequest->headers->set('Authorization', $all['authorization']);
            }
        }

        return $BridgeRequest;
    }
}
