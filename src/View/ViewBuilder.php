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

namespace BrightNucleus\View;

use BrightNucleus\Config\ConfigFactory;
use BrightNucleus\Config\ConfigInterface;
use BrightNucleus\Config\ConfigTrait;
use BrightNucleus\Config\Exception\FailedToProcessConfigException;
use BrightNucleus\View\Engine\BaseEngineFinder;
use BrightNucleus\View\Engine\Engine;
use BrightNucleus\View\Engine\EngineFinder;
use BrightNucleus\View\View\ViewFinder;
use BrightNucleus\View\Exception\FailedToInstantiateView;
use BrightNucleus\View\Location\Locations;
use BrightNucleus\View\Location\Location;

/**
 * Class ViewBuilder.
 *
 * @since   0.1.0
 *
 * @package BrightNucleus\View
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
class ViewBuilder
{

    use ConfigTrait;

    const ENGINE_FINDER_KEY = 'EngineFinder';
    const VIEW_FINDER_KEY = 'ViewFinder';

    /**
     * BaseViewFinder instance.
     *
     * @since 0.1.0
     *
     * @var ViewFinder
     */
    protected $viewFinder;

    /**
     * BaseEngineFinder instance.
     *
     * @since 0.1.0
     *
     * @var BaseEngineFinder
     */
    protected $engineFinder;

    /**
     * Locations to scan for views.
     *
     * @since 0.1.0
     *
     * @var Locations
     */
    protected $locations;

    /**
     * Instantiate a ViewBuilder object.
     *
     * @since 0.1.0
     *
     * @param ConfigInterface       $config       Optional. Configuration settings.
     * @param ViewFinder|null       $viewFinder   Optional. BaseViewFinder instance.
     * @param BaseEngineFinder|null $engineFinder Optional. BaseEngineFinder instance.
     *
     * @throws FailedToProcessConfigException If the config could not be processed.
     */
    public function __construct(
        ConfigInterface $config = null,
        ViewFinder $viewFinder = null,
        BaseEngineFinder $engineFinder = null
    ) {
        $this->processConfig($this->getConfig($config));
        $this->viewFinder   = $viewFinder;
        $this->engineFinder = $engineFinder;
        $this->locations    = new Locations();
    }

    /**
     * Create a new view for a given URI.
     *
     * @since 0.1.0
     *
     * @param string $view View identifier to create a view for.
     * @param mixed  $type Type of view to create.
     *
     * @return View Instance of the requested view.
     * @throws FailedToInstantiateView If the view could not be instantiated.
     */
    public function create(string $view, $type = null): View
    {
        $uri    = $this->scanLocations([$view]);
        $engine = $uri
            ? $this->getEngine($uri)
            : false;

        return ($uri && $engine)
            ? $this->getView($uri, $engine, $type)
            : $this->getViewFinder()->getNullObject();
    }

    /**
     * Get an Engine that can deal with the given URI.
     *
     * @since 0.1.0
     *
     * @param string $uri URI to get an engine for.
     *
     * @return Engine Instance of an engine that can deal with the given URI.
     */
    public function getEngine(string $uri): Engine
    {
        return $this->getEngineFinder()->find([$uri]);
    }

    /**
     * Get a view for a given URI, engine and type.
     *
     * @since 0.1.0
     *
     * @param string $uri    URI to get a view for.
     * @param Engine $engine Engine to use for the view.
     * @param mixed  $type   Type of view to get.
     *
     * @return View View that matches the given requirements.
     * @throws FailedToInstantiateView If the view could not be instantiated.
     */
    public function getView(string $uri, Engine $engine, $type = null): View
    {
        $view = (null === $type)
            ? $this->getViewFinder()->find([$uri], $engine)
            : $this->resolveType($type, $uri, $engine);

        return $view->setBuilder($this);
    }

    /**
     * Get the ViewFinder instance.
     *
     * @since 0.1.0
     *
     * @return ViewFinder Instance of a BaseViewFinder.
     */
    public function getViewFinder(): ViewFinder
    {
        return $this->getFinder($viewFinder, static::VIEW_FINDER_KEY);
    }

    /**
     * Get the EngineFinder instance.
     *
     * @since 0.1.0
     *
     * @return EngineFinder Instance of a BaseEngineFinder.
     */
    public function getEngineFinder(): EngineFinder
    {
        return $this->getFinder($this->engineFinder, static::ENGINE_FINDER_KEY);
    }

    /**
     * Add a location to scan with the BaseViewFinder.
     *
     * @since 0.1.0
     *
     * @param Location $location Location to scan with the BaseViewFinder.
     */
    public function addLocation(Location $location)
    {
        $this->locations->add($location);
    }

    /**
     * Get the collection of locations registered with this ViewBuilder.
     *
     * @since 0.1.3
     *
     * @return Locations Collection of locations.
     */
    public function getLocations()
    {
        return $this->locations;
    }

    /**
     * Scan Locations for an URI that matches the specified criteria.
     *
     * @since 0.1.0
     *
     * @param array $criteria Criteria to match.
     *
     * @return string|false URI of the requested view, or false if not found.
     */
    public function scanLocations(array $criteria)
    {
        $uris = $this->locations->map(function ($location) use ($criteria) {
            /** @var Location $location */
            return $location->getURI($criteria);
        })->filter(function ($uri) {
            return false !== $uri;
        });

        return $uris->count() > 0 ? $uris->first() : false;
    }

    /**
     * Get a finder instance.
     *
     * @since 0.1.1
     *
     * @param mixed  $property Property to use.
     * @param string $key      Configuration key to use.
     *
     * @return ViewFinder|EngineFinder The requested finder instance.
     */
    protected function getFinder(&$property, $key)
    {
        if (null === $property) {
            $finderClass = $this->config->getKey($key, 'ClassName');
            $property    = new $finderClass($this->config->getSubConfig($key));
        }

        return $property;
    }

    /**
     * Resolve the view type.
     *
     * @since 0.1.0
     *
     * @param mixed       $type   Type of view that was requested.
     * @param string      $uri    URI to get a view for.
     * @param Engine|null $engine Engine to use for the view.
     *
     * @return View Resolved View object.
     * @throws FailedToInstantiateView If the view type could not be resolved.
     */
    protected function resolveType($type, string $uri, Engine $engine = null): View
    {
        $configKey = [static::VIEW_FINDER_KEY, 'Views', $type];

        if (is_string($type) && $this->config->hasKey($configKey)) {
            $className = $this->config->getKey($configKey);
            $type      = new $className($uri, $engine);
        }

        if (is_string($type)) {
            $type = new $type($uri, $engine);
        }

        if (is_callable($type)) {
            $type = $type($uri, $engine);
        }

        if (! $type instanceof View) {
            throw new FailedToInstantiateView(
                sprintf(
                    _('Could not instantiate view "%s".'),
                    serialize($type)
                )
            );
        }

        return $type;
    }

    /**
     * Get the configuration to use in the ViewBuilder.
     *
     * @since 0.2.0
     *
     * @param ConfigInterface|array $config Config to merge with defaults.
     *
     * @return ConfigInterface Configuration passed in through the constructor.
     */
    protected function getConfig($config = []): ConfigInterface
    {
        $defaults = ConfigFactory::create(dirname(__DIR__, 2) . '/config/defaults.php', $config);
        $config   = $config
            ? ConfigFactory::createFromArray(array_merge_recursive($defaults->getArrayCopy(), $config->getArrayCopy()))
            : $defaults;

        return $config->getSubConfig('BrightNucleus\View');
    }
}
