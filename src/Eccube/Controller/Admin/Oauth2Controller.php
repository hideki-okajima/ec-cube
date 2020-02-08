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
use Eccube\Form\Type\OAuth2\ClientType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Trikoder\Bundle\OAuth2Bundle\Manager\ClientManagerInterface;
use Trikoder\Bundle\OAuth2Bundle\Model\Client;
use Trikoder\Bundle\OAuth2Bundle\Model\Grant;
use Trikoder\Bundle\OAuth2Bundle\Model\RedirectUri;
use Trikoder\Bundle\OAuth2Bundle\Model\Scope;

/**
 * Oauth2Controller
 *
 * APIクライアントを管理するためのコントローラ
 */
class Oauth2Controller extends AbstractController
{
    /**
     * @var ClientManagerInterface
     */
    private $clientManager;

    /**
     * Oauth2Controller constructor.
     *
     * @param ClientManagerInterface $clientManager
     */
    public function __construct(ClientManagerInterface $clientManager)
    {
        $this->clientManager = $clientManager;
    }

    /**
     * OAuth2クライアントを新規作成する.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @throws \Exception
     *
     * @Route("/%eccube_admin_route%/setting/system/oauth2/client/create", name="admin_setting_system_oauth2_client_create")
     * @Template("@admin/OAuth2/edit.twig")
     */
    public function createClient(Request $request)
    {
        $builder = $this->formFactory->createBuilder(ClientType::class);

        /** @var Form $form */
        $form = $builder->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $identifier = $form->get('identifier')->getData() ?? hash('md5', random_bytes(16));
            $secret = $form->get('secret')->getData() ?? hash('sha512', random_bytes(32));

            $client = new Client($identifier, $secret);

            $client = $this->updateClientFromForm($client, $form);
            $this->clientManager->save($client);
            $this->addSuccess('New oAuth2 client created successfully.', 'admin');

            return $this->redirectToRoute('admin_setting_system_oauth2_client_create'); // TODO
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * OAuth2クライアントを編集する.
     *
     * @param Request $request
     * @param string|null $identifier
     *
     * @return array|RedirectResponse
     *
     * @throws \Exception
     * @Route("/%eccube_admin_route%/setting/system/oauth2/client/{identifier}/update", requirements={"identifier" = ".+"}, name="admin_setting_system_oauth2_client_update")
     * @Template("@admin/OAuth2/edit.twig")
     */
    public function updateClient(Request $request, string $identifier = null)
    {
        $client = $this->clientManager->find($identifier);

        if (null === $client) {
            throw new NotFoundHttpException(sprintf('oAuth2 client identified as "%s"', $identifier));
        }

        $builder = $this->formFactory->createBuilder(ClientType::class);

        /** @var Form $form */
        $form = $builder->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $client = $this->updateClientFromForm($client, $form);
            $this->clientManager->save($client);
            $this->addSuccess('Given oAuth2 client updated successfully.', 'admin');

            return $this->redirectToRoute('admin_setting_system_oauth2_client_update', ['identifier' => $identifier]);
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @param Client $client
     * @param Form $form
     *
     * @return Client
     *
     * @throws \Exception
     */
    private function updateClientFromForm(Client $client, Form $form): Client
    {
        $client->setActive($form->get('activated')->getData());

        // TODO 複数の設定に対応
//        $redirectUris = array_map(
//            function (string $redirectUri): RedirectUri { return new RedirectUri($redirectUri); },
//            $form->get('redirect_uri')->getData()
//        );
//        $client->setRedirectUris(...$redirectUris);
        if (is_null($form->get('redirect_uri')->getData())) {
            $redirectUri = [];
        } else {
            $redirectUri[] = new RedirectUri($form->get('redirect_uri')->getData());
        }
        $client->setRedirectUris(...$redirectUri);

        $grants = array_map(
            function (string $grant): Grant { return new Grant($grant); },
            $form->get('grant_type')->getData()
        );
        $client->setGrants(...$grants);

        $scopes = array_map(
            function (string $scope): Scope { return new Scope($scope); },
            $form->get('scope')->getData()
        );
        $client->setScopes(...$scopes);

        return $client;
    }
}
