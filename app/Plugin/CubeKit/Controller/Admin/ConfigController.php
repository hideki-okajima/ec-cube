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

namespace Plugin\CubeKit\Controller\Admin;

use Eccube\Controller\AbstractController;
use Google_Client;
use Plugin\CubeKit\Form\Type\Admin\ConfigType;
use Plugin\CubeKit\Repository\ConfigRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ConfigController extends AbstractController
{
    /**
     * @var ConfigRepository
     */
    protected $configRepository;
    private $google_Client;

    /**
     * ConfigController constructor.
     *
     * @param ConfigRepository $configRepository
     */
    public function __construct(ConfigRepository $configRepository)
    {
        $this->configRepository = $configRepository;
        $this->google_Client = new Google_Client();
        $this->google_Client->setAuthConfig('/Users/hideki_okajima/PhpstormProjects/ec-cube/oauth-credentials.json');
        $redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].'/callback';
        $this->google_Client->setRedirectUri($redirect_uri);
        $this->google_Client->setScopes(\Google_Service_SiteVerification::SITEVERIFICATION);

    }

    /**
     * @Route("/%eccube_admin_route%/cube_kit/config", name="cube_kit_admin_config")
     * @Template("@CubeKit/admin/config.twig")
     */
    public function index(Request $request)
    {
        $Config = $this->configRepository->get();
        $form = $this->createForm(ConfigType::class, $Config);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $Config = $form->getData();
            $this->entityManager->persist($Config);
            $this->entityManager->flush($Config);
            $this->addSuccess('登録しました。', 'admin');

            return $this->redirectToRoute('cube_kit_admin_config');
        }

        return [
            'form' => $form->createView(),
            'oauth_url' => $this->google_Client->createAuthUrl(),
        ];
    }

    /**
     * @Route("/callback", name="cube_kit_callback")
     * @Template("@CubeKit/admin/config.twig")
     */
    public function callback(Request $request)
    {
        $code = $request->query->get('code');
        if (isset($code)) {
            $token = $this->google_Client->fetchAccessTokenWithAuthCode($code);
            // store in the session also
            $this->session->set('cube_kit_id_token', $token);
            // redirect back to the example
            return $this->redirectToRoute('cube_kit_admin_config');
        }
    }

    /**
     * @Route("/get_token", name="cube_kit_get_token")
     */
    public function getToken() {
        $json = [
            "verificationMethod" => "FILE",
            "site" => [
                "identifier" => "http://www.example.com",
                "type" => "SITE"
            ]
        ];
        $accessToken = $this->session->get("cube_kit_id_token");
        $this->google_Client->setAccessToken($accessToken);
        $httpClient = $this->google_Client->authorize();
        $endpoint = "https://www.googleapis.com/siteVerification/v1/token";
        $response =$httpClient->request("POST", $endpoint, ['json' => $json]);

        return $this->json($response->getBody());
    }
}
