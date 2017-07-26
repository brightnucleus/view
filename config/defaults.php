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
    'ClassName'  => Engine\BaseEngineFinder::class,
    'Engines'    => [
        'PHPEngine' => Engine\PHPEngine::class,
    ],
    'NullObject' => Engine\NullEngine::class,
];

$viewFinder = [
    'ClassName'  => View\BaseViewFinder::class,
    'Views'      => [
        'BaseView' => View\BaseView::class,
    ],
    'NullObject' => View\NullView::class,
];

return [
    'BrightNucleus' => [
        'View' => [
            'EngineFinder' => $engineFinder,
            'ViewFinder'   => $viewFinder,
        ],
    ],
];
