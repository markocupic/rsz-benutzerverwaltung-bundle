<?php

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

namespace Markocupic\RszBenutzerverwaltungBundle\Controller\ContaoBackend;

use Contao\CoreBundle\Exception\AccessDeniedException;
use Markocupic\RszBenutzerverwaltungBundle\Excel\RszAddressDownload;
use Markocupic\RszBenutzerverwaltungBundle\Security\RszBackendPermissions;
use PhpOffice\PhpSpreadsheet\Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/contao/_rsz_address_download_all', name: RszAddressDownloadAllController::class, defaults: ['_scope' => 'backend'])]
class RszAddressDownloadAllController
{
    private RszAddressDownload $rszAddressDownload;
    private Security $security;

    public function __construct(Security $security, RszAddressDownload $rszAddressDownload)
    {
        $this->security = $security;
        $this->rszAddressDownload = $rszAddressDownload;
    }

    /**
     * @return Response
     * @throws Exception
     */
    public function __invoke(): Response
    {

        if(!$this->security->isGranted('ROLE_ADMIN') && !$this->security->isGranted(RszBackendPermissions::USER_CAN_DOWNLOAD_RSZ_ADDRESSES, 'main_menu_download'))
{
    throw new AccessDeniedException('Access to this resource has been denied!');
}
        $arrIds = []; // All
        $strOrderBy = 'name';

        return $this->rszAddressDownload->download($arrIds, $strOrderBy);
    }

}
