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

namespace Markocupic\RszBenutzerverwaltungBundle\Cron;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCronJob;
use Contao\CoreBundle\Monolog\ContaoContext;
use Contao\Date;
use Contao\Versions;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Psr\Log\LoggerInterface;

#[AsCronJob('minutely')]
class AutoUpdateWettkampfkategorieCron
{
    public function __construct(
        private readonly Connection $connection,
        private readonly array $rszWettkampfkategorienMap,
        private readonly LoggerInterface|null $contaoGeneralLogger,
    ) {
    }

    public function __invoke(): void
    {
        $this->autoUpdateWettkampfKategorie();
    }

    /**
     * @throws Exception
     */
    public function autoUpdateWettkampfKategorie(): void
    {
        $users = $this->connection->fetchAllAssociative('SELECT id, kategorie, gender, dateOfBirth FROM tl_user');

        foreach ($users as $user) {
            $id = $user['id'];
            $age = $this->getAgeEndCurrentYear((int) $user['dateOfBirth']);
            $strCatOld = $user['kategorie'];
            $strCatNew = $this->getWettkampfkategorie($age, $user['gender']);

            if ($strCatOld !== $strCatNew) {
                // Initialize the version manager
                $objVersions = new Versions('tl_user', $id);
                $objVersions->initialize();

                $set = [
                    'tstamp' => time(),
                    'kategorie' => $strCatNew,
                ];

                // Update tl_user.kategorie
                $this->connection->update('tl_user', $set, ['id' => $id]);

                // Create a new version
                $objVersions->create(true);

                // Contao system log
                $text = sprintf('Column tl_user.wettkampfkategorie for backend user with ID %d has been auto-updated from "%s" to "%s".', $id, $strCatOld, $strCatNew);
                $this->contaoGeneralLogger?->info(
                    $text,
                    ['contao' => new ContaoContext(__METHOD__, 'AUTO_UPDATE_WETTKAMPFKATEGORIE')],
                );
            }
        }
    }

    private function getAgeEndCurrentYear(int $dateOfBirth): int
    {
        return (int) Date::parse('Y') - (int) Date::parse('Y', $dateOfBirth);
    }

    /**
     * @throws \Exception
     */
    private function getWettkampfkategorie(int $age, string $gender): string
    {
        foreach ($this->rszWettkampfkategorienMap as $catName => $arrCat) {
            if ($gender !== $arrCat['gender']) {
                continue;
            }

            if (2 !== \count($arrCat['age'])) {
                throw new \Exception('Malformed array exception. The rsz_benutzerverwaltung.wettkampfkategorie.age parameter has to be an array counting exactly 2 values.');
            }

            $rangeAge = range((int) $arrCat['age'][0], (int) $arrCat['age'][1]);

            if (\in_array($age, $rangeAge, true)) {
                return $catName;
            }
        }

        return '';
    }
}
