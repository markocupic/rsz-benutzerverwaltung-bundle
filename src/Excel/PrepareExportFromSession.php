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

namespace Markocupic\RszBenutzerverwaltungBundle\Excel;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Helper methods for generating a user export depending on the
 * filter, search and order settings
 * in the Contao backend.
 */
class PrepareExportFromSession
{
    private Connection $connection;
    private RequestStack $requestStack;

    public function __construct(Connection $connection, RequestStack $requestStack)
    {
        $this->connection = $connection;
        $this->requestStack = $requestStack;
    }

    /**
     * @throws Exception
     */
    public function getIdsFromSession(): array
    {
        /** @var QueryBuilder $qb */
        $qb = $this->connection->createQueryBuilder();

        $qb->select('id')
            ->from('tl_user', 't');

        // Get session bag
        $objSessionBag = $this->requestStack->getCurrentRequest()->getSession()->getBag('contao_backend');

        // Filter
        if ($objSessionBag->has('filter')) {
            $filter = $objSessionBag->get('filter');

            if (isset($filter['tl_user']) && !empty($filter['tl_user'])) {
                foreach ($filter['tl_user'] as $k => $v) {
                    if ('limit' !== $k) {
                        $exprOr = $qb->expr()->or(
                            // If field is a serialized array
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
        $strOrder = 'dateAdded DESC';

        $objSessionBag = $this->requestStack->getCurrentRequest()->getSession()->getBag('contao_backend');
        $orderBy = $objSessionBag->get('sorting');

        if (isset($orderBy['tl_user']) && !empty($orderBy['tl_user'])) {
            $strOrder = $orderBy['tl_user'];
        }

        return $strOrder;
    }
}
