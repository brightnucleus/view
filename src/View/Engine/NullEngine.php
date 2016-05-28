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

use BrightNucleus\View\Support\NullObject;

/**
 * Class NullEngine.
 *
 * @since   0.1.0
 *
 * @package BrightNucleus\View\Engine
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
class NullEngine implements EngineInterface, NullObject
{

    /**
     * Check whether the engine can render a given URI.
     *
     * @since 0.1.0
     *
     * @param string $uri URI that wants to be rendered.
     *
     * @return bool Whether the engine can render the given URI.
     */
    public function canRender($uri)
    {
        return true;
    }

    /**
     * Render a given URI.
     *
     * @since 0.1.0
     *
     * @param string $uri     URI to render.
     * @param array  $context Context in which to render.
     *
     * @return string Rendered HTML.
     */
    public function render($uri, array $context = [])
    {
        return '';
    }
}
