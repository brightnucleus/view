<?php declare(strict_types=1);
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

use BrightNucleus\Views;
use BrightNucleus\View\Location\FilesystemLocation;
use BrightNucleus\View\Tests\TestCase;

/**
 * Class ViewBuilderTest.
 *
 * @since   0.1.0
 *
 * @package BrightNucleus\View
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
class ViewFacadeTest extends TestCase
{

    /**
     * @dataProvider PHPViewDataProvider
     *
     * @param string     $view
     * @param array|null $extensions
     */
    public function testPHPView(string $view, $extensions)
    {
        Views::addLocation(new FilesystemLocation(__DIR__ . '/fixtures', $extensions));
        $view = Views::create($view);
        $this->assertInstanceOf('BrightNucleus\View\View\BaseView', $view);
        $html = $view->render(['title' => 'Dynamic Title']);
        $this->assertEquals(file_get_contents(__DIR__ . '/fixtures/php-view-result.html'), $html);
    }

    /**
     * @dataProvider PHPViewDataProvider
     *
     * @param string     $view
     * @param array|null $extensions
     */
    public function testPHPViewDirectRender(string $view, $extensions)
    {
        Views::addLocation(new FilesystemLocation(__DIR__ . '/fixtures', $extensions));
        $html = Views::render($view, ['title' => 'Dynamic Title']);
        $this->assertEquals(file_get_contents(__DIR__ . '/fixtures/php-view-result.html'), $html);
    }

    public function PHPViewDataProvider(): array
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
        Views::addLocation(new FilesystemLocation(__DIR__ . '/fixtures', ['.php']));
        $view = Views::create(__DIR__ . '/fixtures/php-view.php');
        $this->assertInstanceOf('BrightNucleus\View\View\BaseView', $view);
        $html = $view->render(['title' => 'Dynamic Title']);
        $this->assertEquals(file_get_contents(__DIR__ . '/fixtures/php-view-result.html'), $html);
    }
}
