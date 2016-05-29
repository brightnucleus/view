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

use BrightNucleus\View\Exception\FailedToInstantiateEngineException;
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
     * @return mixed Result of the search.
     */
    public function find(array $criteria)
    {
        $this->initializeEngines();

        foreach ($criteria as $entry) {
            foreach ($this->findables as $engine) {
                if ($engine->canHandle($entry)) {
                    return $engine;
                }
            }
        }

        return $this->nullObject;
    }

    /**
     * Initialize the engines that can be iterated.
     *
     * @since 0.1.0
     *
     */
    protected function initializeEngines()
    {
        foreach ($this->findables as &$engine) {
            $engine = $this->initializeEngine($engine);
        }

        if (! is_object($this->nullObject)) {
            $this->nullObject = new $this->nullObject();
        }
    }

    /**
     * Initialize a single engine by instantiating class name strings and calling closures.
     *
     * @since 0.1.0
     *
     * @param mixed $engine Engine to instantiate.
     *
     * @return EngineInterface Instantiated engine.
     * @throws FailedToInstantiateEngineException If the engine could not be instantiated.
     */
    protected function initializeEngine($engine)
    {
        if (is_string($engine)) {
            $engine = new $engine();
        }

        if (is_callable($engine)) {
            $engine = $engine();
        }

        if (! $engine instanceof EngineInterface) {
            throw new FailedToInstantiateEngineException(
                sprintf(
                    _('Could not instantiate engine "%s".'),
                    serialize($engine)
                )
            );
        }

        return $engine;
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
