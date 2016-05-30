<?php
/**
 * LocationCollection Test.
 *
 * @package   BrightNucleus\View
 * @author    Alain Schlesser <alain.schlesser@gmail.com>
 * @license   MIT
 * @link      http://www.brightnucleus.com/
 * @copyright 2016 Alain Schlesser, Bright Nucleus
 */

namespace BrightNucleus\View;

use BrightNucleus\View\Location\FilesystemLocation;
use BrightNucleus\View\Location\LocationCollection;

/**
 * Class LocationCollectionTest.
 *
 * @since   0.1.0
 *
 * @package BrightNucleus\View
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
class LocationCollectionTest extends \PHPUnit_Framework_TestCase
{

    public function testAddingKnownLocations()
    {
        $root = __DIR__ . '/fixtures/locations';

        $locations  = new LocationCollection();
        $locationA1 = new FilesystemLocation($root . '/locationA', []);
        $locationA2 = new FilesystemLocation($root . '/locationA', []);
        $locationA3 = new FilesystemLocation($root . '/locationA', ['.ext']);
        $locationA4 = new FilesystemLocation($root . '/locationA', ['.ext']);
        $locationB1 = new FilesystemLocation($root . '/locationB', ['.ext1']);
        $locationB2 = new FilesystemLocation($root . '/locationB', ['.ext2']);
        $locationB3 = new FilesystemLocation($root . '/locationB', ['.ext3']);

        $this->assertCount(0, $locations);
        $locations->add($locationA1);
        $this->assertCount(1, $locations);
        $locations->add($locationA2);
        $this->assertCount(1, $locations);
        $locations->add($locationA3);
        $this->assertCount(2, $locations);
        $locations->add($locationA4);
        $this->assertCount(2, $locations);
        $locations->add($locationB1);
        $this->assertCount(3, $locations);
        $locations->add($locationB2);
        $this->assertCount(4, $locations);
        $locations->add($locationB3);
        $this->assertCount(5, $locations);
    }
}
