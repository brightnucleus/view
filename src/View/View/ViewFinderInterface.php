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

use BrightNucleus\View\Support\FinderInterface;
use BrightNucleus\View\View\ViewInterface;

/**
 * Interface ViewFinderInterface.
 *
 * @since   0.1.0
 *
 * @package BrightNucleus\View\View
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
interface ViewFinderInterface extends FinderInterface
{

    /**
     * Find a result based on a specific criteria.
     *
     * @since 0.1.0
     *
     * @param array                $criteria Criteria to search for.
     * @param EngineInterface|null $engine   Optional. Engine to use with the view.
     *
     * @return ViewInterface View that was found.
     */
    public function find(array $criteria, EngineInterface $engine = null);
}
