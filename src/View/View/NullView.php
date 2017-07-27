<?php declare(strict_types=1);
/**
 * Bright Nucleus View Component.
 *
 * @package   BrightNucleus\View
 * @author    Alain Schlesser <alain.schlesser@gmail.com>
 * @license   MIT
 * @link      http://www.brightnucleus.com/
 * @copyright 2016 Alain Schlesser, Bright Nucleus
 */

namespace BrightNucleus\View\View;

use BrightNucleus\View\Support\NullFindable;
use BrightNucleus\View\View;
use BrightNucleus\View\ViewBuilder;

/**
 * Class NullView.
 *
 * @since   0.1.0
 *
 * @package BrightNucleus\View\View
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
class NullView implements View, NullFindable
{

    /**
     * Check whether the Findable can handle an individual criterion.
     *
     * @since 0.1.0
     *
     * @param mixed $criterion Criterion to check.
     *
     * @return bool Whether the Findable can handle the criterion.
     */
    public function canHandle($criterion): bool
    {
        return true;
    }

    /**
     * Render the view.
     *
     * @since 0.1.0
     *
     * @param array $context Optional. The context in which to render the view.
     * @param bool  $echo    Optional. Whether to echo the output immediately. Defaults to false.
     *
     * @return string Rendered HTML.
     */
    public function render(array $context = [], bool $echo = false): string
    {
        return '';
    }

    /**
     * Render a partial view (or section) for a given URI.
     *
     * @since 0.2.0
     *
     * @param string      $view    View identifier to create a view for.
     * @param array       $context Optional. The context in which to render the view.
     * @param string|null $type    Type of view to create.
     *
     * @return string Rendered HTML content.
     */
    public function section(string $view, array $context = [], $type = null): string
    {
        return '';
    }

    /**
     * Get the entire array of contextual data.
     *
     * @since 0.4.0
     *
     * @return array Array of contextual data.
     */
    public function getContext(): array
    {
        return [];
    }

    /**
     * Associate a view builder with this view.
     *
     * @since 0.2.0
     *
     * @param ViewBuilder $builder
     *
     * @return View
     */
    public function setBuilder(ViewBuilder $builder): View
    {
        return $this;
    }
}
