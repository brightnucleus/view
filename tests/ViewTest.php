<?php declare( strict_types=1 );
/**
 * View Test.
 *
 * @package   BrightNucleus\View
 * @author    Alain Schlesser <alain.schlesser@gmail.com>
 * @license   MIT
 * @link      http://www.brightnucleus.com/
 * @copyright 2016-2017 Alain Schlesser, Bright Nucleus
 */

namespace BrightNucleus\View\Tests;

use BrightNucleus\View\Engine\NullEngine;
use BrightNucleus\View\View;
use BrightNucleus\View\View\BaseView;
use BrightNucleus\View\Tests\TestCase;

/**
 * Class ViewBuilderTest.
 *
 * @since   0.1.0
 *
 * @package BrightNucleus\View
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
class ViewTest extends TestCase
{

    /**
     * @dataProvider addToContextProvider
     *
     * @param array  $context  Existing context to use.
     * @param string $key      Key to add.
     * @param mixed  $value    Value to add.
     * @param string $behavior Behavior to use for adding to context.
     * @param array  $result   Resulting context to expect.
     */
    public function testAddToContext(array $context, string $key, $value, string $behavior, array $result)
    {
        $view = new BaseView('some_uri', new NullEngine(), null, $context);
        $this->assertEquals($context, $view->getContext());
        $view->addToContext($key, $value, $behavior);
        $this->assertEquals($result, $view->getContext());
    }

    public function addToContextProvider()
    {
        return [
            [ [ 'key' => 'value' ], 'key', 'other_value', View::REPLACE,      [ 'key' => 'other_value' ] ],
            [ [ 'key' => 'value' ], 'key', 'other_value', View::MERGE,        [ 'key' => [ 'value', 'other_value' ] ] ],
            [ [ 'key' => 'value' ], 'key', 'other_value', View::ADD_ONLY,     [ 'key' => 'value' ] ],
            [ [ 'key' => 'value' ], 'key', 'other_value', View::REPLACE_ONLY, [ 'key' => 'other_value' ] ],
            [ [ 'key' => 'value' ], 'key', 'other_value', View::MERGE_ONLY,   [ 'key' => [ 'value', 'other_value' ] ] ],
            [ [ 'key' => 'value' ], 'other_key', 'other_value', View::REPLACE,      [ 'key' => 'value', 'other_key' => 'other_value' ] ],
            [ [ 'key' => 'value' ], 'other_key', 'other_value', View::MERGE,        [ 'key' => 'value', 'other_key' => 'other_value' ] ],
            [ [ 'key' => 'value' ], 'other_key', 'other_value', View::ADD_ONLY,     [ 'key' => 'value', 'other_key' => 'other_value' ] ],
            [ [ 'key' => 'value' ], 'other_key', 'other_value', View::REPLACE_ONLY, [ 'key' => 'value' ] ],
            [ [ 'key' => 'value' ], 'other_key', 'other_value', View::MERGE_ONLY,   [ 'key' => 'value' ] ],
        ];
    }
}
