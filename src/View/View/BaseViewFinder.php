<?php
/**
 * Bright Nucleus View Component.
 *
 * @package   BrightNucleus\View
 * @author    Alain Schlesser <alain.schlesser@gmail.com>
 * @license   MIT
 * @link      http://www.brightnucleus.com/
 * @copyright 2016 Alain Schlesser, Bright Nucleus
 */

namespace BrightNucleus\View\View;

use BrightNucleus\View\Engine\Engine;
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
class BaseViewFinder extends AbstractFinder
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
     */
    public function find(array $criteria, Engine $engine = null)
    {
        $uri = $criteria[0];

        $this->initializeFindables([$uri, $engine]);

        foreach ($criteria as $entry) {
            foreach ($this->findables as $viewObject) {
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
    protected function getFindablesConfigKey()
    {
        return 'Views';
    }
}
