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
     * @var array
     */
    protected $extensions;

    /**
     * Instantiate a FilesystemLocation object.
     *
     * @since 0.1.0
     *
     * @param string $path       Path that this location points to.
     * @param array  $extensions Array of extensions that this location can accept.
     */
    public function __construct($path, array $extensions = [])
    {
        $this->path       = $path;
        $this->extensions = array_merge($extensions, ['']);
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
            foreach ($this->extensions as $extension) {

                $variants = [
                    $entry . $extension,
                    $this->path . DIRECTORY_SEPARATOR . $entry . $extension,
                ];

                foreach ($variants as $uri) {
                    if (is_readable($uri)) {
                        if ($firstOnly) {
                            return $uri;
                        }
                        $uris [] = $uri;
                    }
                }
            }
        } catch (Exception $exception) {
            // Fail silently.
        }

        return $firstOnly ? false : $uris;
    }
}
