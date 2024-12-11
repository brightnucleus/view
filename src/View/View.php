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

namespace BrightNucleus\View;

use BrightNucleus\View\Support\Findable;

/**
 * Interface View.
 *
 * @since   0.1.0
 *
 * @package BrightNucleus\View\View
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
interface View extends Findable
{

    const MERGE        = 'merge';
    const REPLACE      = 'replace';
    const ADD_ONLY     = 'add-only';
    const REPLACE_ONLY = 'replace-only';
    const MERGE_ONLY   = 'merge-only';

    /**
     * Check whether the Findable can handle an individual criterion.
     *
     * @since 0.1.0
     *
     * @param mixed $criterion Criterion to check.
     *
     * @return bool Whether the Findable can handle the criterion.
     */
    public function canHandle($criterion): bool;

    /**
     * Render the view.
     *
     * @since 0.1.0
     *
     * @param array $context Optional. The context in which to render the view.
     *
     * @return string Rendered HTML.
     */
    public function render(array $context = []): string;

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
    public function section(string $view, array $context = [], $type = null): string;

    /**
     * Get the entire array of contextual data.
     *
     * @since 0.4.0
     *
     * @return array Array of contextual data.
     */
    public function getContext(): array;

    /**
     * Add information to the context.
     *
     * @param string $key      Context key to add.
     * @param mixed  $value    Value to add under the given key.
     * @param string $behavior Behavior to use for adapting the context.
     * @return View
     */
    public function addToContext(string $key, $value, string $behavior): View;

    /**
     * Associate a view builder with this view.
     *
     * @since 0.2.0
     *
     * @param ViewBuilder $builder
     *
     * @return View
     */
    public function setBuilder(ViewBuilder $builder): View;
}
