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

namespace BrightNucleus\View\Location;

/**
 * Interface LocationInterface.
 *
 * @since   0.1.0
 *
 * @package BrightNucleus\View\Location
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
interface LocationInterface
{

    /**
     * Get an URI that matches the given criteria.
     *
     * @since 0.1.0
     *
     * @param array $criteria Criteria to match.
     *
     * @return string|false URI that matches the criteria or false if none found.
     */
    public function getURI(array $criteria);
}