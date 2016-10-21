<?php
/**
 * ViewBuilder Test.
 *
 * @package   BrightNucleus\View
 * @author    Alain Schlesser <alain.schlesser@gmail.com>
 * @license   MIT
 * @link      http://www.brightnucleus.com/
 * @copyright 2016 Alain Schlesser, Bright Nucleus
 */

namespace BrightNucleus\View;

use BrightNucleus\Config\ConfigFactory;
use BrightNucleus\View\Location\FilesystemLocation;

/**
 * Class ViewBuilderTest.
 *
 * @since   0.1.0
 *
 * @package BrightNucleus\View
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
class ViewBuilderTest extends \PHPUnit_Framework_TestCase
{

    /** @var ViewBuilder */
    protected $viewBuilder;

    public function setUp()
    {
        $this->viewBuilder = new ViewBuilder(
            ConfigFactory::create(__DIR__ . '/../config/defaults.php')
                         ->getSubConfig('BrightNucleus\View')
        );
    }

    /** @dataProvider PHPViewDataProvider */
    public function testPHPView($view, $extensions)
    {
        $this->viewBuilder->addLocation(new FilesystemLocation(__DIR__ . '/fixtures', $extensions));
        $view = $this->viewBuilder->create($view);
        $this->assertInstanceOf('BrightNucleus\View\View\BaseView', $view);
        $html = $view->render(['title' => 'Dynamic Title']);
        $this->assertEquals(file_get_contents(__DIR__ . '/fixtures/php-view-result.html'), $html);
    }

    public function PHPViewDataProvider()
    {
        return [
            // string $view, array $extensions
            ['php-view', ['.php']],
            ['php-view', ['.zip', '.txt', '.php', '.html']],
            ['php-view.php', ['.php']],
            ['php-view.php', ['.zip', '.txt', '.php', '.html']],
            ['php-view.php', ['']],
            ['php-view.php', []],
            ['php-view.php', null],
        ];
    }

    public function testPHPViewWithFullPath()
    {
        $this->viewBuilder->addLocation(new FilesystemLocation(__DIR__ . '/fixtures', ['.php']));
        $view = $this->viewBuilder->create(__DIR__ . '/fixtures/php-view.php');
        $this->assertInstanceOf('BrightNucleus\View\View\BaseView', $view);
        $html = $view->render(['title' => 'Dynamic Title']);
        $this->assertEquals(file_get_contents(__DIR__ . '/fixtures/php-view-result.html'), $html);
    }

    public function testNullView()
    {
        $this->viewBuilder->addLocation(new FilesystemLocation(__DIR__ . '/nonsense', []));
        $view = $this->viewBuilder->create('nonsense');
        $this->assertInstanceOf('BrightNucleus\View\View\NullView', $view);
        $html = $view->render(['title' => 'Dynamic Title']);
        $this->assertEquals('', $html);
    }

    /** @dataProvider combinedPHPViewDataProvider */
    public function testCombinedPHPView($view, $extensions, $needle)
    {
        $this->viewBuilder->addLocation(new FilesystemLocation(__DIR__ . '/fixtures', $extensions));
        $view = $this->viewBuilder->create($view);
        $this->assertInstanceOf('BrightNucleus\View\View\BaseView', $view);
        $html = $view->render(['title' => 'Dynamic Title']);
        $this->assertContains($needle, $html);
    }

    public function combinedPHPViewDataProvider()
    {
        return [
            // string $view, array $extensions, string $needle
            ['testA.typeA', ['.php'], 'AA'],
            ['testA.typeB', ['.php'], 'AB'],
            ['testB.typeA', ['.php'], 'BA'],
            ['testB.typeB', ['.php'], 'BB'],
        ];
    }

    /** @dataProvider partialPHPViewDataProvider */
    public function testPartialPHPView($view, $extensions, $needle)
    {
        $this->viewBuilder->addLocation(new FilesystemLocation(__DIR__ . '/fixtures', $extensions));
        $view = $this->viewBuilder->create($view);
        $this->assertInstanceOf('BrightNucleus\View\View\BaseView', $view);
        $html = $view->render(['title' => 'Dynamic Title']);
        $this->assertContains($needle, $html);
    }

    public function partialPHPViewDataProvider()
    {
        return [
            // string $view, array $extensions, string $needle
            ['partial.parent', ['.php'], 'PARTIAL.PARENT-PARTIAL.CHILD1-PARTIAL.CHILD2'],
        ];
    }
}
