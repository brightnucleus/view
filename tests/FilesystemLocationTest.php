<?php declare(strict_types=1);
/**
 * FilesystemLocation Test.
 *
 * @package   BrightNucleus\View
 * @author    Alain Schlesser <alain.schlesser@gmail.com>
 * @license   MIT
 * @link      http://www.brightnucleus.com/
 * @copyright 2016 Alain Schlesser, Bright Nucleus
 */

namespace BrightNucleus\View;

use BrightNucleus\View\Location\FilesystemLocation;
use BrightNucleus\View\Location\URIs;

/**
 * Class FilesystemLocationTest.
 *
 * @since   0.1.0
 *
 * @package BrightNucleus\View
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
class FilesystemLocationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider fileSystemLocationDataProvider
     *
     * @param string $path
     * @param array  $extensions
     * @param array  $criteria
     * @param string $expectedFirstURI
     * @param URIs   $expectedURIs
     */
    public function testFilesystemLocation(
        string $path,
        array $extensions,
        array $criteria,
        string $expectedFirstURI,
        URIs $expectedURIs
    ) {
        $location = new FilesystemLocation($path, $extensions);
        $firstURI = $location->getURI($criteria);
        $this->assertEquals($expectedFirstURI, $firstURI);
        $allURIs = $location->getURIs($criteria);
        $this->assertEquals($expectedURIs, $allURIs);
    }

    public function fileSystemLocationDataProvider(): array
    {
        $root = __DIR__ . '/fixtures/locations';

        return [
            // string $path, array $extensions, array $criteria, string $expectedFirstURI, URIs $expectedURIs
            [
                $root . '/flat',
                ['.php'],
                ['test1'],
                $root . '/flat/test1.php',
                new URIs([
                    $root . '/flat/test1.php',
                ]),
            ],
            [
                $root . '/flat',
                ['.php', '.html'],
                ['test1'],
                $root . '/flat/test1.php',
                new URIs([
                    $root . '/flat/test1.php',
                    $root . '/flat/test1.html',
                ]),
            ],
            [
                $root . '/flat',
                ['.html', '.php'],
                ['test1'],
                $root . '/flat/test1.html',
                new URIs([
                    $root . '/flat/test1.html',
                    $root . '/flat/test1.php',
                ]),
            ],
            [
                $root . '/recursive',
                ['.html', '.php'],
                ['testA1'],
                $root . '/recursive/levelA1/testA1.php',
                new URIs([
                    $root . '/recursive/levelA1/testA1.php',
                ]),
            ],
            [
                $root . '/recursive',
                ['.html', '.php'],
                ['testA2'],
                $root . '/recursive/levelA1/levelA2/testA2.php',
                new URIs([
                    $root . '/recursive/levelA1/levelA2/testA2.php',
                ]),
            ],
            [
                $root . '/recursive',
                ['.html', '.php'],
                ['testA3'],
                $root . '/recursive/levelA1/levelA2/levelA3/testA3.php',
                new URIs([
                    $root . '/recursive/levelA1/levelA2/levelA3/testA3.php',
                ]),
            ],
            [
                $root . '/recursive',
                ['.html', '.php'],
                ['testB1'],
                $root . '/recursive/levelB1/testB1.php',
                new URIs([
                    $root . '/recursive/levelB1/testB1.php',
                ]),
            ],
            [
                $root . '/recursive',
                ['.html', '.php'],
                ['testB2'],
                $root . '/recursive/levelB1/levelB2/testB2.php',
                new URIs([
                    $root . '/recursive/levelB1/levelB2/testB2.php',
                ]),
            ],
            [
                $root . '/recursive',
                ['.html', '.php'],
                ['testB3'],
                $root . '/recursive/levelB1/levelB2/levelB3/testB3.php',
                new URIs([
                    $root . '/recursive/levelB1/levelB2/levelB3/testB3.php',
                ]),
            ],
            [
                $root . '/recursive',
                ['.php', '.html'],
                ['testC'],
                $root . '/recursive/php/testC.php',
                new URIs([
                    $root . '/recursive/php/testC.php',
                    $root . '/recursive/html/testC.html',
                ]),
            ],
            [
                $root . '/recursive',
                ['.php', '.html'],
                ['testA.typeA'],
                $root . '/recursive/combined/testA.typeA.php',
                new URIs([
                    $root . '/recursive/combined/testA.typeA.php',
                ]),
            ],
            [
                $root . '/recursive',
                ['.php', '.html'],
                ['testA.typeB'],
                $root . '/recursive/combined/testA.typeB.php',
                new URIs([
                    $root . '/recursive/combined/testA.typeB.php',
                ]),
            ],
            [
                $root . '/recursive',
                ['.php', '.html'],
                ['testB.typeA'],
                $root . '/recursive/combined/testB.typeA.php',
                new URIs([
                    $root . '/recursive/combined/testB.typeA.php',
                ]),
            ],
            [
                $root . '/recursive',
                ['.php', '.html'],
                ['testB.typeB'],
                $root . '/recursive/combined/testB.typeB.php',
                new URIs([
                    $root . '/recursive/combined/testB.typeB.php',
                ]),
            ],
        ];
    }
}
