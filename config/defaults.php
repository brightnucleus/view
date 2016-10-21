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
    'ClassName'  => 'BrightNucleus\View\Engine\BaseEngineFinder',
    'Engines'    => [
        'PHPEngine' => 'BrightNucleus\View\Engine\PHPEngine',
    ],
    'NullObject' => 'BrightNucleus\View\Engine\NullEngine',
];

$viewFinder = [
    'ClassName'  => 'BrightNucleus\View\View\BaseViewFinder',
    'Views'      => [
        'BaseView' => 'BrightNucleus\View\View\BaseView',
    ],
    'NullObject' => 'BrightNucleus\View\View\NullView',
];

return [
    'BrightNucleus' => [
        'View' => [
            'EngineFinder' => $engineFinder,
            'ViewFinder'   => $viewFinder,
        ],
    ],
];
