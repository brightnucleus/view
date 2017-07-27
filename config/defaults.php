<?php
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

/*
 * Engine finder default configuration.
 */
$engineFinder = [
    // Class to use for instantiating the EngineFinder implementation.
    Engine\EngineFinder::CLASS_NAME_KEY => Engine\BaseEngineFinder::class,
    // Engine implementations to register with the EngineFinder.
    Engine\EngineFinder::ENGINES_KEY    => [
        'PHPEngine' => Engine\PHPEngine::class,
    ],
    // Null object implementation to use with the EngineFinder.
    Engine\EngineFinder::NULL_OBJECT    => Engine\NullEngine::class,
];

/*
 * View finder default configuration.
 */
$viewFinder = [
    // Class to use for instantiating the ViewFinder implementation.
    View\ViewFinder::CLASS_NAME_KEY => View\BaseViewFinder::class,
    // View implementations to register with the ViewFinder.
    View\ViewFinder::VIEWS_KEY      => [
        'BaseView' => View\BaseView::class,
    ],
    // Null object implementation to use with the ViewFinder.
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
