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

/**
 * Interface Finder.
 *
 * @since   0.1.0
 *
 * @package BrightNucleus\View\Support
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
interface Finder
{

    /**
     * Find a result based on a specific criteria.
     *
     * @since 0.1.0
     *
     * @param array $criteria Criteria to search for.
     *
     * @return mixed Result of the search.
     */
    public function find(array $criteria);

    /**
     * Get the NullObject.
     *
     * @since 0.1.1
     *
     * @return NullObject NullObject for the current Finder.
     */
    public function getNullObject();
}
