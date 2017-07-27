<?php declare(strict_types=1);
/**
 * Bright Nucleus View Component.
 *
 * @package   BrightNucleus\View
 * @author    Alain Schlesser <alain.schlesser@gmail.com>
 * @license   MIT
 * @link      http://www.brightnucleus.com/
 * @copyright 2016-2017 Alain Schlesser, Bright Nucleus
 */

namespace BrightNucleus\View\Location;

use BrightNucleus\View\Support\Extensions;
use BrightNucleus\View\Support\URIHelper;
use Exception;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Class FilesystemLocation.
 *
 * @since   0.1.0
 *
 * @package BrightNucleus\View\Location
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
class FilesystemLocation implements Location
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
     * @var Extensions
     */
    protected $extensions;

    /**
     * Instantiate a FilesystemLocation object.
     *
     * @since 0.1.0
     *
     * @param string                       $path       Path that this location points to.
     * @param Extensions|array|string|null $extensions Optional. Extensions that this location can accept.
     */
    public function __construct(string $path, $extensions = null)
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
        $uris = $this->getURIs($criteria);

        return $uris->count() > 0
            ? $this->getURIs($criteria)->first()
            : false;
    }

    /**
     * Get all URIs that match the given criteria.
     *
     * @since 0.1.1
     *
     * @param array $criteria Criteria to match.
     *
     * @return URIs URIs that match the criteria or an empty collection if none found.
     */
    public function getURIs(array $criteria): URIs
    {
        $uris = new URIs();

        foreach ($this->extensions as $extension) {
            $finder = new Finder();

            try {
                $finder->files()
                    ->name($this->getNamePattern($criteria, $extension))
                    ->in($this->getPathPattern());
                foreach ($finder as $file) {
                    /** @var SplFileInfo $file */
                    $uris->add($file->getPathname());
                }
            } catch (Exception $exception) {
                // Fail silently;
            }
        }

        return $uris;
    }

    /**
     * Get the name pattern to pass to the file finder.
     *
     * @since 0.1.3
     *
     * @param array  $criteria  Criteria to match.
     * @param string $extension Extension to match.
     *
     * @return string Name pattern to pass to the file finder.
     */
    protected function getNamePattern(array $criteria, string $extension): string
    {
        $names = [];

        $names[] = array_map(function ($criterion) use ($extension) {
            $criterion = URIHelper::getFilename($criterion);

            return empty($extension) || URIHelper::hasExtension($criterion, $extension)
                ? $criterion
                : $criterion . $extension;
        }, $criteria)[0];

        return $this->arrayToRegexPattern(array_unique($names));
    }

    /**
     * Get the path pattern to pass to the file finder.
     *
     * @since 0.1.3
     *
     * @return string Path pattern to pass to the file finder.
     */
    protected function getPathPattern(): string
    {
        return $this->path;
    }

    /**
     * Get an array as a regular expression pattern string.
     *
     * @since 0.1.3
     *
     * @param array $array Array to generate the pattern for.
     *
     * @return string Generated regular expression pattern.
     */
    protected function arrayToRegexPattern(array $array): string
    {
        $array = array_map(function ($entry) {
            return preg_quote($entry);
        }, $array);

        return '/' . implode('|', $array) . '/';
    }

    /**
     * Validate the extensions and return a collection.
     *
     * @since 0.1.1
     *
     * @param Extensions|array|string|null $extensions Extensions to validate.
     *
     * @return Extensions Validated extensions collection.
     */
    protected function validateExtensions($extensions): Extensions
    {
        if (empty($extensions)) {
            $extensions = new Extensions(['']);
        }

        if (! $extensions instanceof Extensions) {
            $extensions = new Extensions((array)$extensions);
        }

        return $extensions;
    }
}
