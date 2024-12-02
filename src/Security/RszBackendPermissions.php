<?php

declare(strict_types=1);

/*
 * This file is part of RSZ Benutzerverwaltung Bundle.
 *
 * (c) Marko Cupic <m.cupic@gmx.ch>
 * @license MIT
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/rsz-benutzerverwaltung-bundle
 */

namespace Markocupic\RszBenutzerverwaltungBundle\Security;

final class RszBackendPermissions
{
    /**
     * Access is granted if the current user can download addresses.
     * Subject must be an operation: main_menu_download.
     */
    public const USER_CAN_DOWNLOAD_RSZ_ADDRESSES = 'contao_user.rsz_address_downloadp';

    /**
     * Access is granted if the current user can perform a given operation on the user table.
     * Subject must be an operation: can_edit_rsz_users or can_delete_rsz_users.
     */
    public const RSZ_USERS_PERMISSIONS = 'contao_user.rsz_usersp';
}
