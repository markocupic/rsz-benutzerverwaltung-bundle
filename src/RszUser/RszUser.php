<?php

/**
 * @copyright  Marko Cupic 2020 <m.cupic@gmx.ch>
 * @author     Marko Cupic
 * @license    MIT
 *
 * @see        https://github.com/markocupic/rsz-benutzerverwaltung-bundle
 */

declare(strict_types=1);

/*
 * This file is part of RSZ Benutzerverwaltung Bundle.
 *
 * (c) Marko Cupic 2023 <m.cupic@gmx.ch>
 * @license MIT
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/rsz-benutzerverwaltung-bundle
 */

namespace Markocupic\RszBenutzerverwaltungBundle\RszUser;


use Contao\UserModel;



class RszUser
{

    public const DEFAULT_AVATAR_FEMALE = 'bundles/markocupicrszbenutzerverwaltung/female-1.png';
    public const DEFAULT_AVATAR_MALE = 'bundles/markocupicrszbenutzerverwaltung/male-1.png';





    public static function getAvatar(int $userId): string
    {
        $objUser = UserModel::findByPk($userId);

        if (null !== $objUser) {
            if ('' !== $objUser->avatar) {
                $objFile = FilesModel::findByUuid($objUser->avatar);

                if (null !== $objFile) {
                    return $objFile->path;
                }
            } else {
                if ('female' === $objUser->gender) {
                    return self::DEFAULT_AVATAR_FEMALE;
                }

                return self::DEFAULT_AVATAR_MALE;
            }
        }

        return self::DEFAULT_AVATAR_MALE;
    }









}
