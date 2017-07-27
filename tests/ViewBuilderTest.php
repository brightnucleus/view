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
            ConfigFactory::create(dirname(__DIR__) . '/config/defaults.php')
                ->getSubConfig(__NAMESPACE__)
        );
    }

    /**
     * @dataProvider PHPViewDataProvider
     *
     * @param string     $view
     * @param array|null $extensions
     */
    public function testPHPView(string $view, $extensions)
    {
        $this->viewBuilder->addLocation(new FilesystemLocation(__DIR__ . '/fixtures', $extensions));
        $view = $this->viewBuilder->create($view);
        $this->assertInstanceOf(View\BaseView::class, $view);
        $html = $view->render(['title' => 'Dynamic Title']);
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
        $this->viewBuilder->addLocation(new FilesystemLocation(__DIR__ . '/fixtures', ['.php']));
        $view = $this->viewBuilder->create(__DIR__ . '/fixtures/php-view.php');
        $this->assertInstanceOf(View\BaseView::class, $view);
        $html = $view->render(['title' => 'Dynamic Title']);
        $this->assertEquals(file_get_contents(__DIR__ . '/fixtures/php-view-result.html'), $html);
    }

    public function testNullView()
    {
        $this->viewBuilder->addLocation(new FilesystemLocation(__DIR__ . '/nonsense', []));
        $view = $this->viewBuilder->create('nonsense');
        $this->assertInstanceOf(View\NullView::class, $view);
        $html = $view->render(['title' => 'Dynamic Title']);
        $this->assertEquals('', $html);
    }

    /**
     * @dataProvider combinedPHPViewDataProvider
     *
     * @param string $view
     * @param array  $extensions
     * @param string $needle
     */
    public function testCombinedPHPView(string $view, array $extensions, string $needle)
    {
        $this->viewBuilder->addLocation(new FilesystemLocation(__DIR__ . '/fixtures', $extensions));
        $view = $this->viewBuilder->create($view);
        $this->assertInstanceOf(View\BaseView::class, $view);
        $html = $view->render(['title' => 'Dynamic Title']);
        $this->assertContains($needle, $html);
    }

    public function combinedPHPViewDataProvider(): array
    {
        return [
            // string $view, array $extensions, string $needle
            ['testA.typeA', ['.php'], 'AA'],
            ['testA.typeB', ['.php'], 'AB'],
            ['testB.typeA', ['.php'], 'BA'],
            ['testB.typeB', ['.php'], 'BB'],
        ];
    }

    /**
     * @dataProvider partialPHPViewDataProvider
     *
     * @param string $view
     * @param array  $extensions
     * @param string $needle
     */
    public function testPartialPHPView(string $view, array $extensions, string $needle)
    {
        $this->viewBuilder->addLocation(new FilesystemLocation(__DIR__ . '/fixtures', $extensions));
        $view = $this->viewBuilder->create($view);
        $this->assertInstanceOf(View\BaseView::class, $view);
        $html = $view->render(['title' => 'Dynamic Title']);
        $this->assertContains($needle, $html);
    }

    public function partialPHPViewDataProvider(): array
    {
        return [
            // string $view, array $extensions, string $needle
            ['partial.parent', ['.php'], 'PARTIAL.PARENT-PARTIAL.CHILD1-PARTIAL.CHILD2'],
        ];
    }

    public function testCustomConfig()
    {
        $viewBuilder = new ViewBuilder(ConfigFactory::create([
            'BrightNucleus' => [
                'View' => [
                    'EngineFinder' => [
                        'Engines' => [
                            'TestEngine' => Tests\TestEngine::class,
                        ],
                    ],
                ],
            ],
        ]));
        $viewBuilder->addLocation(new FilesystemLocation(__DIR__ . '/fixtures', ['.test']));
        $view   = $viewBuilder->create('custom.engine');
        $result = $view->render(['testdata' => 'testvalue']);
        $this->assertEquals('Test Data = testvalue', $result);
    }
}
