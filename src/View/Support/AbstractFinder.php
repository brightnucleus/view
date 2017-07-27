<?php declare(strict_types=1);
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
use BrightNucleus\View\Exception\FailedToInstantiateFindable;

/**
 * Abstract class AbstractFinder.
 *
 * @since   0.1.0
 *
 * @package BrightNucleus\View\Support
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
abstract class AbstractFinder implements Finder
{

    use ConfigTrait;

    /**
     * Findable collection that the Finder can iterate through to find a match.
     *
     * @since 0.1.0
     *
     * @var Findables
     */
    protected $findables;

    /**
     * NullObject that is returned if the Finder could not find a match.
     *
     * @since 0.1.0
     *
     * @var NullFindable
     */
    protected $nullObject;

    /**
     * Instantiate an AbstractFinder object.
     *
     * @since 0.1.0
     *
     * @param ConfigInterface $config Configuration of the AbstractFinder.
     *
     * @throws FailedToProcessConfigException If the config could not be processed.
     */
    public function __construct(ConfigInterface $config)
    {
        $this->processConfig($config);
        $this->findables = new Findables();
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
        $findables = (array) $config->getKey($this->getFindablesConfigKey());
        foreach ($findables as $findableKey => $findableObject) {
            $this->findables->set($findableKey, $findableObject);
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
     * @return NullFindable NullObject for the current Finder.
     * @throws FailedToInstantiateFindable If the Findable could not be instantiated.
     */
    public function getNullObject(): NullFindable
    {
        $this->initializeNullObject();

        return $this->nullObject;
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
        return 'Findables';
    }

    /**
     * Get the config key for the NullObject definitions.
     *
     * @since 0.1.0
     *
     * @return string Config key use to define the NullObject.
     */
    protected function getNullObjectConfigKey(): string
    {
        return 'NullObject';
    }

    /**
     * Initialize the NullObject.
     *
     * @since 0.1.1
     *
     * @param mixed $arguments Optional. Arguments to use.
     *
     * @throws FailedToInstantiateFindable If the Findable could not be instantiated.
     */
    protected function initializeNullObject($arguments = null)
    {
        $this->nullObject = $this->maybeInstantiateFindable($this->nullObject, $arguments);
    }

    /**
     * Initialize the Findables that can be iterated.
     *
     * @param mixed $arguments Optional. Arguments to use.
     *
     * @since 0.1.0
     *
     * @throws FailedToInstantiateFindable If the Findable could not be instantiated.
     */
    protected function initializeFindables($arguments = null)
    {
        $this->findables = $this->findables->map(function ($findable) use ($arguments) {
            return $this->initializeFindable($findable, $arguments);
        });
    }

    /**
     * Initialize a single findable by instantiating class name strings and calling closures.
     *
     * @since 0.1.0
     *
     * @param mixed $findable  Findable to instantiate.
     * @param mixed $arguments Optional. Arguments to use.
     *
     * @return Findable Instantiated findable.
     * @throws FailedToInstantiateFindable If the Findable could not be instantiated.
     */
    protected function initializeFindable($findable, $arguments = null): Findable
    {
        return $this->maybeInstantiateFindable($findable, $arguments);
    }

    /**
     * Maybe instantiate a Findable if it is not yet an object.
     *
     * @since 0.1.1
     *
     * @param mixed $findable  Findable to instantiate.
     * @param mixed $arguments Optional. Arguments to use.
     *
     * @return Findable Instantiated findable.
     * @throws FailedToInstantiateFindable If the findable could not be instantiated.
     */
    protected function maybeInstantiateFindable($findable, $arguments = null): Findable
    {
        if (is_string($findable)) {
            $findable = $this->instantiateFindableFromString($findable, $arguments);
        }

        if (is_callable($findable)) {
            $findable = $this->instantiateFindableFromCallable($findable, $arguments);
        }

        if (! $findable instanceof Findable) {
            throw new FailedToInstantiateFindable(
                sprintf(
                    _('Could not instantiate Findable "%s".'),
                    serialize($findable)
                )
            );
        }

        return $findable;
    }

    /**
     * Instantiate a Findable from a string.
     *
     * @since 0.1.1
     *
     * @param string $string    String to use for instantiation.
     * @param mixed  $arguments Optional. Arguments to use for instantiation.
     *
     * @return Findable Instantiated Findable.
     */
    protected function instantiateFindableFromString(string $string, $arguments = []): Findable
    {
        return new $string(...(array)$arguments);
    }

    /**
     * Instantiate a Findable from a callable.
     *
     * @since 0.1.1
     *
     * @param callable $callable  Callable to use for instantiation.
     * @param mixed    $arguments Optional. Arguments to use for instantiation.
     *
     * @return Findable Instantiated Findable.
     */
    protected function instantiateFindableFromCallable(callable $callable, $arguments = []): Findable
    {
        return $callable(...(array)$arguments);
    }
}
