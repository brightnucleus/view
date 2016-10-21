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

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Extensions.
 *
 * @since   0.1.1
 *
 * @package BrightNucleus\View\Support
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
class Extensions extends ArrayCollection
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
    public static function hasExtension($uri, $extension)
    {
        $uriLength       = mb_strlen($uri);
        $extensionLength = mb_strlen($extension);
        if ($extensionLength > $uriLength) {
            return false;
        }

        return substr_compare($uri, $extension, $uriLength - $extensionLength, $extensionLength) === 0;
    }
}
