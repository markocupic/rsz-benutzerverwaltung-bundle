<?php

/**
 * @copyright  Marko Cupic 2020 <m.cupic@gmx.ch>
 * @author     Marko Cupic
 * @package    RSZ Benutzerverwaltung
 * @license    MIT
 * @see        https://github.com/markocupic/rsz-benutzerverwaltung-bundle
 *
 */

namespace Markocupic\RszBenutzerverwaltungBundle\EventListener\ContaoHooks;

use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\Widget;
use Contao\CoreBundle\ServiceAnnotation\Hook;
use Terminal42\ServiceAnnotationBundle\ServiceAnnotationInterface;

/**
 * Class AddCustomRegexpListener
 * @package Markocupic\RszBenutzerverwaltungBundle\EventListener\ContaoHooks
 */
class AddCustomRegexpListener implements ServiceAnnotationInterface
{
    /**
     * @var ContaoFramework
     */
    private $framework;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * AddCustomRegexpListener constructor.
     * @param ContaoFramework $framework
     * @param RequestStack $requestStack
     */
    public function __construct(ContaoFramework $framework, RequestStack $requestStack)
    {
        $this->framework = $framework;
        $this->requestStack = $requestStack;
    }

    /**
     * Überprüfe, ob Name und Vorname übergeben wurden (mind. 2 Wörter)
     *
     * @Hook("addCustomRegexp")       *
     * @param string $strRegexp
     * @param string $varValue
     * @param Widget $objWidget
     * @return bool
     */
    public function isFirstnameAndLastname(string $strRegexp, string $varValue, Widget $objWidget): bool
    {
        // Überprüfe, ob Name und Vorname übergeben wurden (mind. 2 Wörter)
        if ($strRegexp === 'name')
        {
            if (strpos(trim($varValue), ' ') === false)
            {
                $objWidget->addError('Der Name sollte aus mindestens zwei durch einen Leerschlag voneinander getrennten Wörtern bestehen.');
            }

            return true;
        }

        return false;
    }
}
