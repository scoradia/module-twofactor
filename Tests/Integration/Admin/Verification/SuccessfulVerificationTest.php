<?php
/**
 * A two factor authentication module that protects both the admin and customer logins
 * Copyright (C) 2017  Ross Mitchell
 *
 * This file is part of Rossmitchell/Twofactor.
 *
 * Rossmitchell/Twofactor is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Rossmitchell\Twofactor\Tests\Integration\Admin\Verification;

use Rossmitchell\Twofactor\Model\GoogleTwoFactor\QRCode;
use Rossmitchell\Twofactor\Tests\Integration\Abstracts\AbstractTestClass;
use Rossmitchell\Twofactor\Tests\Integration\FixtureLoader\Traits\AdminUserLoader;
use Rossmitchell\Twofactor\Tests\Integration\FixtureLoader\Traits\ConfigurationLoader;

class SuccessfulVerificationTest extends AbstractTestClass
{
    use AdminUserLoader;
    use ConfigurationLoader;

    public static function getAdminUserDataPath()
    {
        return __DIR__ . '/../_files/adminUser.php';
    }

    public static function getConfigurationDataPath()
    {
        return __DIR__.'/../_files/two_factor_enabled.php';
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoDataFixture loadAdminUsers
     * @magentoDataFixture loadConfiguration
     */
    public function testVerificationPasses()
    {
        $this->loginAdmin('two_factor_enabled', 'password123');
        $this->getRequest()
            ->setMethod('POST')
            ->setParam('secret', $this->getValidCode('WSQO22WRQ4MRQG2SW3YTSVCLCGKCPSWG'));
        $this->dispatch('/backend/twofactor/adminlogin/verify');

        $this->assertRedirect($this->stringContains('admin/dashboard/index'));
    }

    private function getValidCode($secret)
    {
        /** @var QRCode $codeGenerator */
        $codeGenerator = $this->createObject(QRCode::class);
        $validCode = $codeGenerator->displayCurrentCode($secret);

        return $validCode;
    }
}
