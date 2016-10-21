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

namespace BrightNucleus\View;

use BrightNucleus\Config\ConfigInterface;
use BrightNucleus\Config\ConfigTrait;
use BrightNucleus\Config\Exception\FailedToProcessConfigException;
use BrightNucleus\View\Engine\EngineFinderInterface;
use BrightNucleus\View\Engine\EngineInterface;
use BrightNucleus\View\Engine\ViewFinderInterface;
use BrightNucleus\View\Exception\FailedToInstantiateViewException;
use BrightNucleus\View\Location\LocationCollection;
use BrightNucleus\View\Location\LocationInterface;
use BrightNucleus\View\Support\FinderInterface;
use BrightNucleus\View\View\ViewInterface;

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
    const VIEW_FINDER_KEY   = 'ViewFinder';

    /**
     * ViewFinder instance.
     *
     * @since 0.1.0
     *
     * @var ViewFinderInterface
     */
    protected $viewFinder;

    /**
     * EngineFinder instance.
     *
     * @since 0.1.0
     *
     * @var EngineFinderInterface
     */
    protected $engineFinder;

    /**
     * Locations to scan for views.
     *
     * @since 0.1.0
     *
     * @var LocationCollection
     */
    protected $locations;

    /**
     * Instantiate a ViewBuilder object.
     *
     * @since 0.1.0
     *
     * @param ConfigInterface            $config       Configuration settings.
     * @param ViewFinderInterface|null   $viewFinder   ViewFinder instance.
     * @param EngineFinderInterface|null $engineFinder EngineFinder instance.
     *
     * @throws FailedToProcessConfigException If the config could not be processed.
     */
    public function __construct(
        ConfigInterface $config,
        ViewFinderInterface $viewFinder = null,
        EngineFinderInterface $engineFinder = null
    ) {
        $this->processConfig($config);
        $this->viewFinder   = $viewFinder;
        $this->engineFinder = $engineFinder;
        $this->locations    = new LocationCollection();
    }

    /**
     * Create a new view for a given URI.
     *
     * @since 0.1.0
     *
     * @param string $view View identifier to create a view for.
     * @param mixed  $type Type of view to create.
     *
     * @return ViewInterface Instance of the requested view.
     */
    public function create($view, $type = null)
    {
        $uri    = $this->scanLocations([$view]);
        $engine = $this->getEngine($uri);

        return $uri
            ? $this->getView($uri, $engine, $type)
            : $this->getViewFinder()->getNullObject();
    }

    /**
     * Get an Engine that can deal with the given URI.
     *
     * @since 0.1.0
     *
     * @param string|false $uri URI to get an engine for.
     *
     * @return EngineInterface Instance of an engine that can deal with the given URI.
     */
    public function getEngine($uri)
    {
        return $this->getEngineFinder()->find([$uri]);
    }

    /**
     * Get a view for a given URI, engine and type.
     *
     * @since 0.1.0
     *
     * @param string          $uri    URI to get a view for.
     * @param EngineInterface $engine Engine to use for the view.
     * @param mixed           $type   Type of view to get.
     *
     * @return ViewInterface View that matches the given requirements.
     */
    public function getView($uri, EngineInterface $engine, $type = null)
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
     * @return ViewFinderInterface Instance of a ViewFinder.
     */
    public function getViewFinder()
    {
        return $this->getFinder($viewFinder, static::VIEW_FINDER_KEY);
    }

    /**
     * Get the EngineFinder instance.
     *
     * @since 0.1.0
     *
     * @return EngineFinderInterface Instance of a EngineFinder.
     */
    public function getEngineFinder()
    {
        return $this->getFinder($this->engineFinder, static::ENGINE_FINDER_KEY);
    }

    /**
     * Add a location to scan with the ViewFinder.
     *
     * @since 0.1.0
     *
     * @param LocationInterface $location Location to scan with the ViewFinder.
     */
    public function addLocation(LocationInterface $location)
    {
        $this->locations->add($location);
    }

    /**
     * Get the collection of locations registered with this ViewBuilder.
     *
     * @since 0.1.3
     *
     * @return LocationCollection Collection of locations.
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
            /** @var LocationInterface $location */
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
     * @return FinderInterface The requested finder instance.
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
     * @param mixed                $type   Type of view that was requested.
     * @param string               $uri    URI to get a view for.
     * @param EngineInterface|null $engine Engine to use for the view.
     *
     * @return ViewInterface Resolved View object.
     * @throws FailedToInstantiateViewException If the view type could not be resolved.
     */
    protected function resolveType($type, $uri, EngineInterface $engine = null)
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

        if ( ! $type instanceof ViewInterface) {
            throw new FailedToInstantiateViewException(
                sprintf(
                    _('Could not instantiate view "%s".'),
                    serialize($type)
                )
            );
        }

        return $type;
    }
}
