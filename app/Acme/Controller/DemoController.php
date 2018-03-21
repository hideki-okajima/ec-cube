<?php
/**
 * Created by PhpStorm.
 * User: hideki_okajima
 * Date: 2018/03/22
 * Time: 5:31
 */

namespace Acme\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

class DemoController
{
    /**
     * @Route("/demo", name="demo")
     * @Template("demo.twig")
     */
    public function demo()
    {
        return ["name" => "Demo"];
    }
}