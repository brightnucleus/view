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

namespace BrightNucleus\View\Location;

use BrightNucleus\View\Support\ExtensionCollection;
use Exception;

/**
 * Class FilesystemLocation.
 *
 * @since   0.1.0
 *
 * @package BrightNucleus\View\Location
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
class FilesystemLocation implements LocationInterface
{

    /**
     * Path that this location points to.
     *
     * @since 0.1.0
     *
     * @var string
     */
    protected $path;

    /**
     * Extensions that this location can accept.
     *
     * @since 0.1.0
     *
     * @var ExtensionCollection
     */
    protected $extensions;

    /**
     * Instantiate a FilesystemLocation object.
     *
     * @since 0.1.0
     *
     * @param string                           $path       Path that this location points to.
     * @param ExtensionCollection|array|string $extensions Extensions that this location can accept.
     */
    public function __construct($path, $extensions = null)
    {
        $this->path       = $path;
        $this->extensions = $this->validateExtensions($extensions);
    }

    /**
     * Get the first URI that matches the given criteria.
     *
     * @since 0.1.0
     *
     * @param array $criteria Criteria to match.
     *
     * @return string|false URI that matches the criteria or false if none found.
     */
    public function getURI(array $criteria)
    {
        foreach ($criteria as $entry) {
            if ($uri = $this->transform($entry, true)) {
                return $uri;
            }
        }

        return false;
    }

    /**
     * Get all URIs that match the given criteria.
     *
     * @since 0.1.1
     *
     * @param array $criteria Criteria to match.
     *
     * @return array URIs that match the criteria or empty array if none found.
     */
    public function getURIs(array $criteria)
    {
        $uris = [];

        foreach ($criteria as $entry) {
            if ($uri = $this->transform($entry, false)) {
                $uris = array_merge($uris, (array)$uri);
            }
        }

        return $uris;
    }

    /**
     * Validate the extensions and return a collection.
     *
     * @since 0.1.1
     *
     * @param ExtensionCollection|array|string $extensions Extensions to validate.
     *
     * @return ExtensionCollection Validated extensions collection.
     */
    protected function validateExtensions($extensions)
    {
        if (! $extensions instanceof ExtensionCollection) {
            $extensions = new ExtensionCollection((array)$extensions);
        }
        $extensions->add('');

        return $extensions;
    }

    /**
     * Try to transform the entry into possible URIs.
     *
     * @since 0.1.0
     *
     * @param string $entry     Entry to transform.
     * @param bool   $firstOnly Return the first result only.
     *
     * @return array|string|false If $firstOnly is true, returns a string with the URI of the view, or false if none
     *                            found.
     *                            If $firstOnly is false, returns an array with all matching URIs, or an empty array if
     *                            none found.
     */
    protected function transform($entry, $firstOnly = true)
    {
        $uris = [];

        try {
            foreach ($this->getVariants($entry) as $uri) {
                if (is_readable($uri)) {
                    if ($firstOnly) {
                        return $uri;
                    }
                    $uris [] = $uri;
                }
            }
        } catch (Exception $exception) {
            // Fail silently.
        }

        return $firstOnly ? false : $uris;
    }

    /**
     * Get the individual variants that could be matched for the location.
     *
     * @since 0.1.1
     *
     * @param string $entry Entry to get the variants for.
     *
     * @return array Array of variants to check.
     */
    protected function getVariants($entry)
    {
        $variants = [];

        $this->extensions->map(function ($extension) use ($entry, &$variants) {
            $variants[] = $entry . $extension;
            $variants[] = $this->path . DIRECTORY_SEPARATOR . $entry . $extension;
        });

        return $variants;
    }
}
