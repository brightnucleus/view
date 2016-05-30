<?php
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
use BrightNucleus\View\Location\URICollection;

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

    /** @dataProvider FileSystemLocationDataProvider */
    public function testFilesystemLocation(
        $path,
        $extensions,
        $criteria,
        $expectedFirstURI,
        URICollection $expectedURIs
    ) {
        $location = new FilesystemLocation($path, $extensions);
        $firstURI = $location->getURI($criteria);
        $this->assertEquals($expectedFirstURI, $firstURI);
        $allURIs = $location->getURIs($criteria);
        $this->assertEquals($expectedURIs, $allURIs);
    }

    public function FileSystemLocationDataProvider()
    {
        $root = __DIR__ . '/fixtures/locations';

        return [
            // string $path, array $extensions, array $criteria, string $expectedFirstURI, URICollection $expectedURIs
            [
                $root . '/flat',
                ['.php'],
                ['test1'],
                $root . '/flat/test1.php',
                new URICollection([
                    $root . '/flat/test1.php',
                ]),
            ],
            [
                $root . '/flat',
                ['.php', '.html'],
                ['test1'],
                $root . '/flat/test1.php',
                new URICollection([
                    $root . '/flat/test1.php',
                    $root . '/flat/test1.html',
                ]),
            ],
            [
                $root . '/flat',
                ['.html', '.php'],
                ['test1'],
                $root . '/flat/test1.html',
                new URICollection([
                    $root . '/flat/test1.html',
                    $root . '/flat/test1.php',
                ]),
            ],
        ];
    }
}
