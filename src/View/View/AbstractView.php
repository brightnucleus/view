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

namespace BrightNucleus\View\View;

use BrightNucleus\Config\Exception\FailedToProcessConfigException;
use BrightNucleus\View\Exception\FailedToInstantiateView;
use BrightNucleus\View\Exception\InvalidContextAddingBehavior;
use BrightNucleus\View\View;
use BrightNucleus\View\Engine\Engine;
use BrightNucleus\View\ViewBuilder;
use BrightNucleus\Views;
use Closure;

/**
 * Abstract class AbstractView.
 *
 * @since   0.1.0
 *
 * @package BrightNucleus\View\View
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
abstract class AbstractView implements View
{

    /**
     * URI of the view.
     *
     * The underscores are used to prevent accidental use of these properties from within the rendering closure.
     *
     * @since 0.1.0
     *
     * @var string
     */
    protected $_uri_;

    /**
     * Engine to use for the view.
     *
     * The underscores are used to prevent accidental use of these properties from within the rendering closure.
     *
     * @since 0.1.0
     *
     * @var Engine
     */
    protected $_engine_;

    /**
     * ViewBuilder instance.
     *
     * The underscores are used to prevent accidental use of these properties from within the rendering closure.
     *
     * @since 0.2.0
     *
     * @var ViewBuilder
     */
    protected $_builder_;

    /**
     * The context with which the view will be rendered.
     *
     * The underscores are used to prevent accidental use of these properties from within the rendering closure.
     *
     * @since 0.4.0
     *
     * @var array
     */
    protected $_context_;

    /**
     * Instantiate an AbstractView object.
     *
     * @since 0.1.0
     *
     * @param string      $uri         URI for the view.
     * @param Engine      $engine      Engine to use for the view.
     * @param ViewBuilder $viewBuilder View builder instance to use.
     * @param array       $context     Initial context to use.
     */
    public function __construct(string $uri, Engine $engine, ViewBuilder $viewBuilder = null, array $context = [])
    {
        $this->_uri_     = $uri;
        $this->_engine_  = $engine;
        $this->_builder_ = $viewBuilder ?? Views::getViewBuilder();
        $this->_context_ = $context;
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
     * @throws FailedToProcessConfigException If the Config could not be processed.
     */
    public function render(array $context = [], bool $echo = false): string
    {
        $this->assimilateContext($context);

        $closure = Closure::bind(
            $this->_engine_->getRenderCallback($this->_uri_, $context),
            $this,
            static::class
        );

        $output = $closure();

        if ($echo) {
            echo $output;
        }

        return $output;
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
     * @throws FailedToProcessConfigException If the Config could not be processed.
     * @throws FailedToInstantiateView If the View could not be instantiated.
     */
    public function section(string $view, array $context = null, $type = null): string
    {
        $context = (null === $context)
            ? $this->_context_
            : array_merge($this->_context_, $context);

        $viewObject = $this->_builder_->create($view, $type);

        return $viewObject->render($context);
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
        return $this->_context_;
    }

    /**
     * Add information to the context.
     *
     * @param string $key      Context key to add.
     * @param mixed  $value    Value to add under the given key.
     * @param string $behavior Behavior to use for adapting the context.
     * @return View
     */
    public function addToContext(string $key, $value, string $behavior): View
    {
        switch ($behavior) {
            case View::REPLACE:
                $this->_context_[$key] = $value;
                return $this;
            case View::MERGE:
                if (array_key_exists($key, $this->_context_)) {
                    $this->_context_ = array_merge_recursive($this->_context_, [$key => $value]);
                    return $this;
                }
                $this->_context_[$key] = $value;
                return $this;
            case View::ADD_ONLY:
                if (array_key_exists($key, $this->_context_)) {
                    return $this;
                }
                $this->_context_[$key] = $value;
                return $this;
            case View::REPLACE_ONLY:
                if (! array_key_exists($key, $this->_context_)) {
                    return $this;
                }
                $this->_context_[$key] = $value;
                return $this;
            case View::MERGE_ONLY:
                if (! array_key_exists($key, $this->_context_)) {
                    return $this;
                }
                $this->_context_ = array_merge_recursive($this->_context_, [$key => $value]);
                return $this;
            default:
                throw new InvalidContextAddingBehavior(
                    sprintf(
                        _('Invalid behavior "%s" for adding to the context of view "%s".'),
                        $key,
                        $this->_uri_
                    )
                );
        }
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
        $this->_builder_ = $builder;

        return $this;
    }

    /**
     * Assimilate the context to make it available as properties.
     *
     * @since 0.2.0
     *
     * @param array $context Context to assimilate.
     */
    protected function assimilateContext(array $context = [])
    {
        $this->_context_ = $context;
        foreach ($context as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Turn invokable objects as properties into methods of the view.
     *
     * @param string $method    Method that was called on the view.
     * @param array  $arguments Array of arguments that were used.
     * @return mixed Return value of the invokable object.
     */
    public function __call($method, $arguments)
    {
        if (! property_exists($this, $method)
             || ! is_callable($this->$method)) {
            trigger_error(
                "Call to undefined method {$method} on a view.",
                E_USER_ERROR
            );
        }

        $callable = $this->$method;

        return $callable(...$arguments);
    }
}
