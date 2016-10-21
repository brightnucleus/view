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

use BrightNucleus\View\Support\Findable;

/**
 * Interface EngineInterface.
 *
 * @since   0.1.0
 *
 * @package BrightNucleus\View\Engine
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
interface EngineInterface extends Findable
{

    /**
     * Get the rendering callback for a given URI.
     *
     * @since 0.1.0
     *
     * @param string $uri     URI to render.
     * @param array  $context Context in which to render.
     *
     * @return string Rendered HTML.
     */
    public function getRenderCallback($uri, array $context = []);
}
