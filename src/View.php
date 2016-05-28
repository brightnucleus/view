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

namespace BrightNucleus;

use BrightNucleus\Config\ConfigFactory;
use BrightNucleus\Config\ConfigInterface;
use BrightNucleus\View\Location\LocationInterface;
use BrightNucleus\View\View\ViewInterface;
use BrightNucleus\View\ViewBuilder;

/**
 * Class View.
 *
 * @since   0.1.0
 *
 * @package BrightNucleus\View
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
class View
{

    /**
     * ViewBuilder Instance.
     *
     * @since 0.1.0
     *
     * @var ViewBuilder
     */
    protected static $viewBuilder;

    /**
     * Add a location to the ViewBuilder.
     *
     * @since 0.1.0
     *
     * @param LocationInterface $location Location to add.
     */
    public static function addLocation(LocationInterface $location)
    {
        $viewBuilder = static::getViewBuilder();
        $viewBuilder->addLocation($location);
    }

    /**
     * Get the ViewBuilder instance.
     *
     * @since 0.1.0
     *
     * @return ViewBuilder
     */
    public static function getViewBuilder()
    {
        if (null === static::$viewBuilder) {
            static::$viewBuilder = static::instantiateViewBuilder();
        }

        return static::$viewBuilder;
    }

    /**
     * Instantiate the ViewBuilder.
     *
     * @since 0.1.0
     *
     * @param ConfigInterface|null $config Optional. Configuration to pass into the ViewBuilder.
     *
     * @return ViewBuilder Instance of the ViewBuilder.
     */
    public static function instantiateViewBuilder(ConfigInterface $config = null)
    {
        return static::$viewBuilder = new ViewBuilder($config ?: static::getDefaultConfig());
    }

    /**
     * Get the default configuration to inject into the ViewBuilder.
     *
     * @since 0.1.0
     *
     * @return ConfigInterface Default configuration.
     */
    public static function getDefaultConfig()
    {
        return ConfigFactory::create(__DIR__ . '/../config/defaults.php')
                            ->getSubConfig('BrightNucleus\View');
    }

    /**
     * Create a new view for a given URI.
     *
     * @since 0.1.0
     *
     * @param string      $view View identifier to create a view for.
     * @param string|null $type Type of view to create.
     *
     * @return ViewInterface Instance of the requested view.
     */
    public static function create($view, $type = null)
    {
        $viewBuilder = static::getViewBuilder();

        return $viewBuilder->create($view, $type);
    }

    /**
     * Render a view for a given URI.
     *
     * @since 0.1.0
     *
     * @param string      $view    View identifier to create a view for.
     * @param array       $context Optional. The context in which to render the view.
     * @param string|null $type    Type of view to create.
     *
     * @return ViewInterface Instance of the requested view.
     */
    public static function render($view, array $context = [], $type = null)
    {
        $viewBuilder = static::getViewBuilder();
        $viewObject  = $viewBuilder->create($view, $type);

        return $viewObject->render($context);
    }
}
