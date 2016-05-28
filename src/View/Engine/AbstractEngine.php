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
 * Abstract class AbstractEngine.
 *
 * @since   0.1.0
 *
 * @package BrightNucleus\View\Engine
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
abstract class AbstractEngine implements EngineInterface, Findable
{

    /**
     * Check whether a given URI has a specific extension.
     *
     * @since 0.1.0
     *
     * @param string $uri       URI to check the extension of.
     * @param string $extension Extension to check for.
     *
     * @return bool
     */
    protected function hasExtension($uri, $extension)
    {
        $uriLength       = mb_strlen($uri);
        $extensionLength = mb_strlen($extension);
        if ($extensionLength > $uriLength) {
            return false;
        }

        return substr_compare($uri, $extension, $uriLength - $extensionLength, $extensionLength) === 0;
    }
}
