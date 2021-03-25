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

use Codeception\Util\Fixtures;
use Page\Admin\AuthorityManagePage;

/**
 * @group ea11
 */
class EA11SecurityCest
{
    public function _before(\AcceptanceTester $I)
    {
        $I->loginAsAdmin();
    }

    public function _after(\AcceptanceTester $I)
    {
    }

    /**
     * ATTENTION 後続のテストが失敗する
     */
    public function systeminfo_security_allow_list(\AcceptanceTester $I)
    {
        $I->wantTo('EA0804-UC01-T03 セキュリティ管理 - IP制限（許可リスト）');

        $findPlugins = Fixtures::get('findPlugins');
        $Plugins = $findPlugins();
        if (is_array($Plugins) && count($Plugins) > 0) {
            $I->getScenario()->skip('プラグインのアンインストールが必要なため、テストをスキップします');
        }

        // 表示
        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['eccube_admin_route'].'/setting/system/security');
        $I->see('セキュリティ管理システム設定', '#page_admin_setting_system_security .c-pageTitle__titles');

        $I->fillField(['id' => 'admin_security_admin_allow_hosts'], '1.1.1.1');
        $I->click('#page_admin_setting_system_security form div.c-contentsArea__cols > div.c-conversionArea > div > div > div:nth-child(2) > div > div > button');

        $I->amOnPage('/'.$config['eccube_admin_route']);
        $I->see('アクセスできません。', '//*[@id="error-page"]//h3');
    }

    /**
     * ATTENTION 後続のテストが失敗する
     */
    public function systeminfo_security_deny_list(\AcceptanceTester $I)
    {
        $I->wantTo('EA0804-UC01-T05 セキュリティ管理 - IP制限（拒否リスト）');

        // 表示
        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['eccube_admin_route'].'/setting/system/security');
        $I->see('セキュリティ管理システム設定', '#page_admin_setting_system_security .c-pageTitle__titles');

        $I->fillField(['id' => 'admin_security_admin_deny_hosts'], '127.0.0.1');
        $I->click('#page_admin_setting_system_security form div.c-contentsArea__cols > div.c-conversionArea > div > div > div:nth-child(2) > div > div > button');

        $I->amOnPage('/'.$config['eccube_admin_route']);
        $I->see('アクセスできません。', '//*[@id="error-page"]//h3');
    }
}
