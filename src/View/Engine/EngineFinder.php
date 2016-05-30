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

namespace BrightNucleus\View\Engine;

use BrightNucleus\View\Support\AbstractFinder;

/**
 * Class EngineFinder.
 *
 * @since   0.1.0
 *
 * @package BrightNucleus\View\Engine
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
class EngineFinder extends AbstractFinder
{

    /**
     * Find a result based on a specific criteria.
     *
     * @since 0.1.0
     *
     * @param array $criteria Criteria to search for.
     *
     * @return EngineInterface Result of the search.
     */
    public function find(array $criteria)
    {
        $this->initializeFindables();

        foreach ($criteria as $entry) {
            foreach ($this->findables as $engine) {
                if ($engine->canHandle($entry)) {
                    return $engine;
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
        return 'Engines';
    }
}
