<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2015 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */


namespace Eccube\Controller\Admin\Setting\Shop;

use Eccube\Application;
use Eccube\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TaxRuleController extends AbstractController
{
    /**
     * 税率設定の初期表示・登録
     *
     * @param  Application $app
     * @param  null $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function index(Application $app, $id = null)
    {
        $TargetTaxRule = null;

        if ($id == null) {
            $TargetTaxRule = $app['eccube.repository.tax_rule']->newTaxRule();
        } else {
            $TargetTaxRule = $app['eccube.repository.tax_rule']->find($id);
            if (is_null($TargetTaxRule)) {
                throw new NotFoundHttpException();
            }
        }

        $builder = $app['form.factory']
            ->createBuilder('tax_rule', $TargetTaxRule);

        if ($TargetTaxRule->isDefaultTaxRule()) {
            // 基本税率設定は適用日時の変更は行わない
            $builder = $builder->remove('apply_date');
        }

        /* @var $form \Symfony\Component\Form\FormInterface */
        $form = $builder->getForm();

        if ($app['request']->getMethod() === 'POST') {
            $form->handleRequest($app['request']);
            if ($form->isValid()) {
                $app['orm.em']->persist($TargetTaxRule);
                $app['orm.em']->flush();

                $app->addSuccess('admin.shop.tax.save.complete', 'admin');

                return $app->redirect($app->url('admin_setting_shop_tax'));
            }
        }

        // 共通税率一覧
        $TaxRules = $app['eccube.repository.tax_rule']->getList();

        return $app->render('Setting/Shop/tax_rule.twig', array(
            'TargetTaxRule' => $TargetTaxRule,
            'TaxRules' => $TaxRules,
            'form' => $form->createView(),
        ));
    }

    /**
     * 特定の共通税率の削除
     *
     * @param  Application $app
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Application $app, $id)
    {
        $TargetTaxRule = $app['eccube.repository.tax_rule']->find($id);
        if (!$TargetTaxRule->isDefaultTaxRule()) {
            $app['eccube.repository.tax_rule']->delete($TargetTaxRule);
            $app->addSuccess('admin.shop.tax.delete.complete', 'admin');
        }

        return $app->redirect($app->url('admin_setting_shop_tax'));
    }

    public function parameterEdit(Application $app, $id)
    {
        //TODO: 商品別税率設定のパラメーター設定の更新、更新後indexへリダイレクト
        return new \Symfony\Component\HttpFoundation\Response('parameterEdit');
    }
}
