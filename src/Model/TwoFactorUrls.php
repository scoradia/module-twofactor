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

namespace Rossmitchell\Twofactor\Model;

use Magento\Framework\UrlInterface;

class TwoFactorUrls
{
    /**
     * @var UrlInterface
     */
    private $url;

    /**
     * TwoFactorUrls constructor.
     *
     * @param UrlInterface $url
     */
    public function __construct(UrlInterface $url)
    {
        $this->url = $url;
    }

    public function getCustomerAuthenticationUrl()
    {
        return $this->url->getUrl('twofactor/customerlogin/index');
    }

    public function getCustomerVerificationUrl()
    {
        return $this->url->getUrl('twofactor/customerlogin/verify');
    }

    public function getCustomerAccountUrl()
    {
        return $this->url->getUrl('customer/account/index');
    }

    public function getAdminDashboardUrl()
    {
        return $this->url->getUrl('admin/dashboard/index');
    }

    public function getCustomerLogInUrl()
    {
        return $this->url->getUrl('customer/account/login');
    }

    public function getAdminAuthenticationUrl()
    {
        return $this->url->getUrl('twofactor/adminlogin/index');
    }

    public function getAdminVerificationUrl()
    {
        return $this->url->getUrl('twofactor/adminlogin/verify');
    }


    public function getCurrentUrl()
    {
        return $this->url->getCurrentUrl();
    }

    public function areWeOnTheAuthenticationPage($forAdmin = false)
    {
        $authenticationUrl = $this->getAuthenticationUrl($forAdmin);

        return $this->compareUrls($this->getCurrentUrl(), $authenticationUrl);
    }

    public function getAuthenticationUrl($forAdmin = false)
    {
        if ($forAdmin === true) {
            return $this->getAdminAuthenticationUrl();
        }

        return $this->getCustomerAuthenticationUrl();
    }

    public function getVerificationUrl($forAdmin = false)
    {
        if ($forAdmin === true) {
            return $this->getAdminVerificationUrl();
        }

        return $this->getCustomerVerificationUrl();
    }

    public function areWeOnTheVerificationPage($forAdmin = false)
    {
        $verificationUrl = $this->getVerificationUrl($forAdmin);

        return $this->compareUrls($this->getCurrentUrl(), $verificationUrl);
    }

    private function compareUrls($firstUrl, $secondUrl)
    {
        return ($this->cleanUrl($firstUrl) === $this->cleanUrl($secondUrl));
    }

    private function cleanUrl($url)
    {
        $parts = parse_url($url);
        $cleanUrl = $parts['host'] . '/' . $parts['path'];
        $noRewriteUrl = str_replace('/index.php', '', $cleanUrl);

        return trim($noRewriteUrl, '\t\n\r/');
    }
}
