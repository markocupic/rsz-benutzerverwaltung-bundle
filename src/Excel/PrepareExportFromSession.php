<?php

declare(strict_types=1);

/*
 * This file is part of RSZ Benutzerverwaltung Bundle.
 *
 * (c) Marko Cupic 2021 <m.cupic@gmx.ch>
 * @license MIT
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/rsz-benutzerverwaltung-bundle
 */

namespace Markocupic\RszBenutzerverwaltungBundle\Excel;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Helper methods for generating a user export depending on the
 * filter, search and order settings
 * in the Contao backend
 *
 * Class PrepareExportFromSession
 * @package Markocupic\RszBenutzerverwaltungBundle\Excel
 */
class PrepareExportFromSession
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * TlUser constructor.
     */
    public function __construct(Connection $connection, SessionInterface $session)
    {
        $this->connection = $connection;
        $this->session = $session;
    }

    /**
     * @throws Exception
     */
    public function getIdsFromSession(): array
    {
        /** @var QueryBuilder $qb */
        $qb = $this->connection->createQueryBuilder();
        $qb->select('id')
            ->from('tl_user', 't')
        ;

        // Get session bag
        $objSessionBag = $this->session->getBag('contao_backend');

        // Filter
        if ($objSessionBag->has('filter')) {
            $filter = $objSessionBag->get('filter');

            if (isset($filter['tl_user']) && !empty($filter['tl_user'])) {
                foreach ($filter['tl_user'] as $k => $v) {
                    if ('limit' !== $k) {
                        $orxOrg = $qb->expr()->orX();
                        // If field is a serialized array
                        $orxOrg->add($qb->expr()->like('t.'.$k, $qb->expr()->literal('%:"'.$v.'";%')));
                        // Else
                        $orxOrg->add($qb->expr()->like('t.'.$k, $qb->expr()->literal('%'.$v.'%')));
                        $qb->andWhere($orxOrg);
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

                    if (!empty($strSearch)) {
                        $qb->andWhere($qb->expr()->like('t.'.$strField, $qb->expr()->literal('%'.$strSearch.'%')));
                    }
                }
            }
        }

        $arrIds = $qb->fetchFirstColumn();

        return \is_array($arrIds) ? $arrIds : [];
    }

    public function getOrderByFromSession(): string
    {
        // Order by
        // Get session bag
        $strOrder = 'dateAdded DESC';
        $objSessionBag = $this->session->getBag('contao_backend');
        $orderBy = $objSessionBag->get('sorting');

        if (isset($orderBy['tl_user']) && !empty($orderBy['tl_user'])) {
            $strOrder = $orderBy['tl_user'];
        }

        return $strOrder;
    }
}
