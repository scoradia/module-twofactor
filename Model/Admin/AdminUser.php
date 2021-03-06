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

namespace Rossmitchell\Twofactor\Model\Admin;

use Magento\Framework\Exception\LocalizedException;
use Magento\User\Model\ResourceModel\User as UserResourceModel;
use Magento\User\Model\User;

class AdminUser
{
    /**
     * @var Session
     */
    private $adminSession;
    /**
     * @var UserResourceModel
     */
    private $resourceModel;

    /**
     * AdminUser constructor.
     *
     * @param Session           $adminSession
     * @param UserResourceModel $resourceModel
     */
    public function __construct(Session $adminSession, UserResourceModel $resourceModel)
    {
        $this->adminSession  = $adminSession;
        $this->resourceModel = $resourceModel;
    }

    /**
     * @return User
     * @throws \Exception
     */
    public function getAdminUser()
    {
        $adminUser = $this->adminSession->getData('user');
        if (!$adminUser instanceof User) {
            throw new LocalizedException(__("No admin user found"));
        }

        return $adminUser;
    }

    public function hasAdminUser()
    {
        return ($this->adminSession->hasData('user') === true);
    }

    public function saveAdminUser(User $user)
    {

        if ($user->isSaveAllowed() === true) {
            $this->resourceModel->save($user->save());
        }
    }
}
