<?php declare(strict_types=1);
/**
 * Bright Nucleus View Component.
 *
 * @package   BrightNucleus\View
 * @author    Alain Schlesser <alain.schlesser@gmail.com>
 * @license   MIT
 * @link      http://www.brightnucleus.com/
 * @copyright 2016-2017 Alain Schlesser, Bright Nucleus
 */

namespace BrightNucleus\View\View;

use BrightNucleus\View\Engine\Engine;
use BrightNucleus\View\Exception\FailedToInstantiateFindable;
use BrightNucleus\View\Support\AbstractFinder;
use BrightNucleus\View\View;

/**
 * Class BaseViewFinder.
 *
 * @since   0.1.0
 *
 * @package BrightNucleus\View\View
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
class BaseViewFinder extends AbstractFinder implements ViewFinder
{

    /**
     * Find a result based on a specific criteria.
     *
     * @since 0.1.0
     *
     * @param array       $criteria Criteria to search for.
     * @param Engine|null $engine   Optional. Engine to use with the view.
     *
     * @return View View that was found.
     * @throws FailedToInstantiateFindable If the Findable could not be instantiated.
     */
    public function find(array $criteria, Engine $engine = null): View
    {
        $uri = $criteria[0];

        $views = $this->initializeFindables([$uri, $engine]);

        foreach ($criteria as $entry) {
            foreach ($views as $viewObject) {
                if ($viewObject->canHandle($entry)) {
                    return $viewObject;
                }
            }
        }

        return $this->getNullObject();
    }

    /**
     * Get the config key for the Findables definitions.
     *
     * @since 0.1.0
     *
     * @return string Config key use to define the Findables.
     */
    protected function getFindablesConfigKey(): string
    {
        return 'Views';
    }

    /**
     * Get the NullObject.
     *
     * @since 0.1.1
     *
     * @return NullView NullObject for the current Finder.
     * @throws FailedToInstantiateFindable If the Findable could not be instantiated.
     */
    public function getNullObject(): NullView
    {
        return parent::getNullObject();
    }
}
