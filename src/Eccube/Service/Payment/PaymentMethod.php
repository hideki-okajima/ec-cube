<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eccube\Service\Payment;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

interface PaymentMethod
{
    /**
     * @return PaymentResult
     */
    public function checkout();

    /**
     * @return PaymentDispatcher
     */
    public function apply();

    /**
     * @param FormInterface
     */
    public function setFormType(FormInterface $form);

    /**
     * @param Request $request
     */
    public function setRequest(Request $request);
}
