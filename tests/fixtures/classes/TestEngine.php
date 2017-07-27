<?php declare(strict_types=1);
/**
 * ViewBuilder Test.
 *
 * @package   BrightNucleus\View
 * @author    Alain Schlesser <alain.schlesser@gmail.com>
 * @license   MIT
 * @link      http://www.brightnucleus.com/
 * @copyright 2016-2017 Alain Schlesser, Bright Nucleus
 */

namespace BrightNucleus\View\Tests;

use BrightNucleus\View\Engine\AbstractEngine;
use BrightNucleus\View\Exception\FailedToLoadView;
use BrightNucleus\View\Support\URIHelper;
use Exception;

class TestEngine extends AbstractEngine
{

    const TEST_EXTENSION = '.test';

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
        return URIHelper::hasExtension($criterion, static::TEST_EXTENSION)
            && is_readable($criterion);
    }

    /**
     * Get the rendering callback for a given URI.
     *
     * @since 0.1.0
     *
     * @param string $uri     URI to render.
     * @param array  $context Context in which to render.
     *
     * @return callable Rendering callback.
     * @throws FailedToLoadView If the View URI is not accessible or readable.
     * @throws FailedToLoadView If the View URI could not be loaded.
     */
    public function getRenderCallback(string $uri, array $context = []): callable
    {
        if (! is_readable($uri)) {
            throw new FailedToLoadView(
                sprintf(
                    _('The View URI "%1$s" is not accessible or readable.'),
                    $uri
                )
            );
        }

        $closure = function () use ($uri, $context) {

            try {
                $template = file_get_contents($uri);

                return str_replace('{{placeholder}}', $context['testdata'], $template);
            } catch (Exception $exception) {
                throw new FailedToLoadView(
                    sprintf(
                        _('Could not load the View URI "%1$s". Reason: "%2$s".'),
                        $uri,
                        $exception->getMessage()
                    ),
                    $exception->getCode(),
                    $exception
                );
            }
        };

        return $closure;
    }
}
