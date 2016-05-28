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
     * @var array<string>
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
     * Get an URI that matches the given criteria.
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
            if ($uri = $this->transform($entry)) {
                return $uri;
            }
        }

        return false;
    }

    /**
     * Try to transform the entry into possible URIs.
     *
     * @since 0.1.0
     *
     * @param string $entry Entry to transform.
     *
     * @return string|bool URI of the view, or false if none found.
     */
    protected function transform($entry)
    {
        try {
            foreach ($this->extensions as $extension) {

                $uri = $entry . $extension;
                if (is_readable($uri)) {
                    return $uri;
                }

                $uri = $this->path . DIRECTORY_SEPARATOR . $uri;
                if (is_readable($uri)) {
                    return $uri;
                }
            }
        } catch (Exception $exception) {
            // Fail silently.
        }

        return false;
    }
}
