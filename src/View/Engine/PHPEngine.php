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

use BrightNucleus\View\Exception\FailedToLoadViewException;
use Exception;

/**
 * Class PHPEngine.
 *
 * @since   0.1.0
 *
 * @package BrightNucleus\View\Engine
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
class PHPEngine extends AbstractEngine
{

    const PHP_EXTENSION = '.php';

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
        return $this->hasExtension($uri, static::PHP_EXTENSION)
               && is_readable($uri);
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
     * @throws FailedToLoadViewException If the View URI is not accessible or readable.
     * @throws FailedToLoadViewException If the View URI could not be loaded.
     */
    public function render($uri, array $context = [])
    {
        if (! is_readable($uri)) {
            throw new FailedToLoadViewException(
                sprintf(
                    _('The View URI "%1$s" is not accessible or readable.'),
                    $uri
                )
            );
        }

        extract($context, EXTR_SKIP);

        // Save current buffering level so we can backtrack in case of an error.
        // This is needed because the view itself might also add an unknown number of output buffering levels.
        $bufferLevel = ob_get_level();
        ob_start();

        try {
            include($uri);
        } catch (Exception $exception) {

            // Remove whatever levels were added up until now.
            while (ob_get_level() > $bufferLevel) {
                ob_end_clean();
            }

            throw new FailedToLoadViewException(
                sprintf(
                    _('Could not load the View URI "%1$s". Reason: "%2$s".'),
                    $uri,
                    $exception->getMessage()
                ),
                $exception->getCode(),
                $exception
            );
        }

        return ob_get_clean();
    }
}