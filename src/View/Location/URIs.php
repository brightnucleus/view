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

namespace BrightNucleus\View\Location;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Finder\Finder;

/**
 * Class URIs.
 *
 * @since   0.1.1
 *
 * @package BrightNucleus\View\Location
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
class URIs extends ArrayCollection
{

    /**
     * Create a new URIs from a Symfony Finder instance.
     *
     * @since 0.1.3
     *
     * @param Finder $finder The Finder instance to create the URI collection from.
     *
     * @return URIs New URIs instance.
     */
    public static function fromFinder(Finder $finder): URIs
    {
        $elements = array_keys(iterator_to_array($finder));

        return new static($elements);
    }
}
