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

namespace BrightNucleus\View;

$engineFinder = [
    Engine\EngineFinder::CLASS_NAME_KEY => Engine\BaseEngineFinder::class,
    Engine\EngineFinder::ENGINES_KEY    => [
        'PHPEngine' => Engine\PHPEngine::class,
    ],
    Engine\EngineFinder::NULL_OBJECT    => Engine\NullEngine::class,
];

$viewFinder = [
    View\ViewFinder::CLASS_NAME_KEY => View\BaseViewFinder::class,
    View\ViewFinder::VIEWS_KEY      => [
        'BaseView' => View\BaseView::class,
    ],
    View\ViewFinder::NULL_OBJECT    => View\NullView::class,
];

return [
    'BrightNucleus' => [
        'View' => [
            'EngineFinder' => $engineFinder,
            'ViewFinder'   => $viewFinder,
        ],
    ],
];
