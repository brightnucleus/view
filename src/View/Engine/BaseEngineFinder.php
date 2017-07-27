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

namespace BrightNucleus\View\Engine;

use BrightNucleus\View\Exception\FailedToInstantiateFindable;
use BrightNucleus\View\Support\AbstractFinder;

/**
 * Class BaseEngineFinder.
 *
 * @since   0.1.0
 *
 * @package BrightNucleus\View\Engine
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
class BaseEngineFinder extends AbstractFinder implements EngineFinder
{

    /**
     * Find a result based on a specific criteria.
     *
     * @since 0.1.0
     *
     * @param array $criteria Criteria to search for.
     *
     * @return Engine Result of the search.
     * @throws FailedToInstantiateFindable If the Findable could not be instantiated.
     */
    public function find(array $criteria): Engine
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
    protected function getFindablesConfigKey(): string
    {
        return 'Engines';
    }
}
