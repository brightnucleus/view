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

use BrightNucleus\View\Engine\EngineInterface;
use BrightNucleus\View\Exception\FailedToInstantiateViewException;
use BrightNucleus\View\Support\AbstractFinder;

/**
 * Class ViewFinder.
 *
 * @since   0.1.0
 *
 * @package BrightNucleus\View\View
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
class ViewFinder extends AbstractFinder
{

    /**
     * Find a result based on a specific criteria.
     *
     * @since 0.1.0
     *
     * @param array           $criteria Criteria to search for.
     * @param EngineInterface $engine   Optional. Engine to use with the view.
     *
     * @return ViewInterface View that was found.
     */
    public function find(array $criteria, EngineInterface $engine = null)
    {
        $uri = $criteria[0];

        $this->initializeViews($uri, $engine);

        foreach ($criteria as $entry) {
            foreach ($this->findables as $viewObject) {
                if ($viewObject->canHandle($entry)) {
                    return $viewObject;
                }
            }
        }

        return $this->nullObject;
    }

    /**
     * Initialize the views that can be iterated.
     *
     * @since 0.1.0
     *
     * @param string          $uri    URI to use for the view.
     * @param EngineInterface $engine Optional. Engine to use with the view.
     */
    protected function initializeViews($uri, EngineInterface $engine = null)
    {
        foreach ($this->findables as &$view) {
            $view = $this->initializeView($view, $uri, $engine);
        }

        $this->nullObject = $this->initializeView($this->nullObject, $uri);
    }

    /**
     * Initialize a single view by instantiating class name strings and calling closures.
     *
     * @since 0.1.0
     *
     * @param mixed           $view   View to instantiate.
     * @param string          $uri    URI to use for the view.
     * @param EngineInterface $engine Optional. Engine to use with the view.
     *
     * @return ViewInterface Instantiated view.
     * @throws FailedToInstantiateViewException If the view could not be instantiated.
     */
    protected function initializeView($view, $uri, EngineInterface $engine = null)
    {
        if (is_string($view)) {
            $view = new $view($uri, $engine);
        }

        if (is_callable($view)) {
            $view = $view($uri, $engine);
        }

        if (! $view instanceof ViewInterface) {
            throw new FailedToInstantiateViewException(
                sprintf(
                    _('Could not instantiate view "%s".'),
                    serialize($view)
                )
            );
        }

        return $view;
    }

    /**
     * Instantiate a view by instantiating class name strings and calling closures.
     *
     * @since 0.1.0
     *
     * @param mixed           $view   View to instantiate.
     * @param string          $uri    URI to use for the view.
     * @param EngineInterface $engine Optional. View to use with the view.
     *
     * @return ViewInterface Instantiated view.
     * @throws FailedToInstantiateViewException If the view could not be instantiated.
     */
    protected function instantiateView($view, $uri, EngineInterface $engine = null)
    {
        if (is_string($view)) {
            $view = new $view($uri, $engine);
        }

        if (is_callable($view)) {
            $view = $view($uri, $engine);
        }

        if (! $view instanceof ViewInterface) {
            throw new FailedToInstantiateViewException(
                sprintf(
                    _('Could not instantiate view "%s".'),
                    serialize($view)
                )
            );
        }

        return $view;
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
