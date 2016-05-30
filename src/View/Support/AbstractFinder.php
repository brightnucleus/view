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

namespace BrightNucleus\View\Support;

use BrightNucleus\Config\ConfigInterface;
use BrightNucleus\Config\ConfigTrait;
use BrightNucleus\Config\Exception\FailedToProcessConfigException;

/**
 * Class EngineFinder.
 *
 * @since   0.1.0
 *
 * @package BrightNucleus\View\Support
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
abstract class AbstractFinder implements FinderInterface
{

    use ConfigTrait;

    /**
     * Array of Findables that the Finder can iterate through to find a match.
     *
     * @since 0.1.0
     *
     * @var Findable[]
     */
    protected $findables;

    /**
     * NullObject that is returned if the Finder could not find a match.
     *
     * @since 0.1.0
     *
     * @var NullObject
     */
    protected $nullObject;

    /**
     * Instantiate an AbstractFinder object.
     *
     * @since 0.1.0
     *
     * @param ConfigInterface $config Configuration of the EngineFinder.
     *
     * @throws FailedToProcessConfigException If the config could not be processed.
     */
    public function __construct(ConfigInterface $config)
    {
        $this->processConfig($config);
        $this->registerFindables($this->config);
        $this->registerNullObject($this->config);
    }

    /**
     * Register the Findables defined in the given configuration.
     *
     * @since 0.1.0
     *
     * @param ConfigInterface $config Configuration to register the Findables from.
     */
    public function registerFindables(ConfigInterface $config)
    {
        foreach ($config->getKey($this->getFindablesConfigKey()) as $findableKey => $findableObject) {
            $this->registerFindable($findableKey, $findableObject);
        }
    }

    /**
     * Register the NullObject defined in the given configuration.
     *
     * @since 0.1.0
     *
     * @param ConfigInterface $config Configuration to register the NullObject from.
     */
    public function registerNullObject(ConfigInterface $config)
    {
        $this->nullObject = $config->getKey($this->getNullObjectConfigKey());
    }

    /**
     * Get the NullObject.
     *
     * @since 0.1.1
     *
     * @return NullObject NullObject for the current Finder.
     */
    public function getNullObject()
    {
        $this->initializeNullObject();

        return $this->nullObject;
    }

    /**
     * Register a single Findable.
     *
     * @since 0.1.0
     *
     * @param string $key      Key used to reference the Findable.
     * @param mixed  $findable Findable as a FQCN, callable or object.
     */
    protected function registerFindable($key, $findable)
    {
        $this->findables[$key] = $findable;
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
        return 'Findables';
    }

    /**
     * Get the config key for the NullObject definitions.
     *
     * @since 0.1.0
     *
     * @return string Config key use to define the NullObject.
     */
    protected function getNullObjectConfigKey()
    {
        return 'NullObject';
    }

    /**
     * Initialize the NullObject.
     *
     * @since 0.1.1
     */
    protected function initializeNullObject()
    {
        if (! is_object($this->nullObject)) {
            $this->nullObject = new $this->nullObject();
        }
    }
}
