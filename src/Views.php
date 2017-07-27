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

namespace BrightNucleus;

use BrightNucleus\Config\ConfigInterface;
use BrightNucleus\Config\Exception\FailedToProcessConfigException;
use BrightNucleus\View\Location\Location;
use BrightNucleus\View\View;
use BrightNucleus\View\ViewBuilder;

/**
 * Class Views.
 *
 * @since   0.1.0
 *
 * @package BrightNucleus\View
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
class Views
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
     * @param Location $location Location to add.
     *
     * @throws FailedToProcessConfigException If the Config could not be processed.
     */
    public static function addLocation(Location $location)
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
     * @throws FailedToProcessConfigException If the Config could not be processed.
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
     * @throws FailedToProcessConfigException If the Config could not be processed.
     */
    public static function instantiateViewBuilder(ConfigInterface $config = null)
    {
        return static::$viewBuilder = new ViewBuilder($config);
    }

    /**
     * Create a new view for a given URI.
     *
     * @since 0.1.0
     *
     * @param string      $view View identifier to create a view for.
     * @param string|null $type Type of view to create.
     *
     * @return View Instance of the requested view.
     * @throws FailedToProcessConfigException If the Config could not be processed.
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
     * @return string Rendered HTML content.
     * @throws FailedToProcessConfigException If the Config could not be processed.
     */
    public static function render($view, array $context = [], $type = null)
    {
        $viewBuilder = static::getViewBuilder();
        $viewObject  = $viewBuilder->create($view, $type);

        return $viewObject->render($context);
    }
}
