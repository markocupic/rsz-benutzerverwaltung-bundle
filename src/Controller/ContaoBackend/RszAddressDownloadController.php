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

use Doctrine\DBAL\Connection;
use Markocupic\RszBenutzerverwaltungBundle\Excel\RszAddressDownload;
use PhpOffice\PhpSpreadsheet\Exception;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/contao/_rsz_address_download', name: 'markocupic_rsz_benutzerverwaltung_rsz_address_download', defaults: ['_scope' => 'backend'])]
class RszAddressDownloadController
{
    private Connection $connection;
    private RequestStack $requestStack;
    private RszAddressDownload $rszAddressDownload;

    public function __construct(Connection $connection, RequestStack $requestStack, RszAddressDownload $rszAddressDownload)
    {
        $this->connection = $connection;
        $this->requestStack = $requestStack;
        $this->rszAddressDownload = $rszAddressDownload;
    }

    /**
     * @throws Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function __invoke(): Response
    {
        $arrIds = $this->getIdsFromSession();
        $strOrderBy = $this->getOrderByFromSession();

        return $this->rszAddressDownload->download($arrIds, $strOrderBy);
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    private function getIdsFromSession(): array
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->select('id')
            ->from('tl_user', 't')
        ;

        // Get session bag
        $objSessionBag = $this->requestStack->getCurrentRequest()->getSession()->getBag('contao_backend');

        // Filter
        if ($objSessionBag->has('filter')) {
            $filter = $objSessionBag->get('filter');

            if (isset($filter['tl_user']) && !empty($filter['tl_user'])) {
                foreach ($filter['tl_user'] as $k => $v) {
                    if ('limit' !== $k) {
                        $exprOr = $qb->expr()->or(
                            // In case that the field content is a serialized array
                            $qb->expr()->like('t.'.$k, $qb->expr()->literal('%:"'.$v.'";%')),
                            // else
                            $qb->expr()->like('t.'.$k, $qb->expr()->literal('%'.$v.'%')),
                        );

                        $qb->andWhere($exprOr);
                    }
                }
            }
        }

        // Search
        if ($objSessionBag->has('search')) {
            $search = $objSessionBag->get('search');

            if (isset($search['tl_user']) && !empty($search['tl_user'])) {
                $arrSearch = $search['tl_user'];

                if (isset($arrSearch['field'], $arrSearch['value']) && !empty($arrSearch['value'])) {
                    $strField = $arrSearch['field'];
                    $strSearch = $arrSearch['value'];

                    $qb->andWhere($qb->expr()->like('t.'.$strField, $qb->expr()->literal('%'.$strSearch.'%')));
                }
            }
        }

        return $qb->fetchFirstColumn();
    }

    private function getOrderByFromSession(): string
    {
        // Order by
        $strOrder = 'dateAdded DESC';

        $objSessionBag = $this->requestStack->getCurrentRequest()->getSession()->getBag('contao_backend');
        $orderBy = $objSessionBag->get('sorting');

        if (isset($orderBy['tl_user']) && !empty($orderBy['tl_user'])) {
            $strOrder = $orderBy['tl_user'];
        }

        return $strOrder;
    }
}
