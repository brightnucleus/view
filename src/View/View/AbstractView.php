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

namespace BrightNucleus\View\View;

use BrightNucleus\View\Engine\EngineInterface;
use BrightNucleus\View\Support\Findable;

/**
 * Abstract class AbstractView.
 *
 * @since   0.1.0
 *
 * @package BrightNucleus\View\View
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
abstract class AbstractView implements ViewInterface, Findable
{

    /**
     * URI of the view.
     *
     * @since 0.1.0
     *
     * @var string
     */
    protected $uri;

    /**
     * Engine to use for the view.
     *
     * @since 0.1.0
     *
     * @var EngineInterface
     */
    protected $engine;

    /**
     * Instantiate an AbstractView object.
     *
     * @since 0.1.0
     *
     * @param string          $uri    URI for the view.
     * @param EngineInterface $engine Engine to use for the view.
     */
    public function __construct($uri, EngineInterface $engine)
    {
        $this->uri    = $uri;
        $this->engine = $engine;
    }

    /**
     * Render the view.
     *
     * @since 0.1.0
     *
     * @param array $context Optional. The context in which to render the view.
     * @param bool  $echo    Optional. Whether to echo the output immediately. Defaults to false.
     *
     * @return string|void Rendered HTML or nothing, depending on $echo argument.
     */
    public function render(array $context = [], $echo = false)
    {
        $output = $this->engine->render($this->uri, $context);
        if ($echo) {
            echo $output;
        } else {
            return $output;
        }
    }
}
