<?php namespace Bono\Lang\Driver;

use Bono\Lang\Contract\AbstractLangDriver;
use RecursiveIteratorIterator as RII;
use RecursiveDirectoryIterator as RDI;

/**
 * Get list of language from driver
 */
class FileDriver extends AbstractLangDriver
{
    /**
     * Configuration
     *
     * @var array
     */
    protected $config = array();

    /**
     * Class constructor
     *
     * @param array $config Configuration
     */
    public function __construct(array $config)
    {
        $this->config = $config;

        if (isset($this->config['lang.path'])) {
            $this->config['lang.path'] = $this->getAppBasePath() . DIRECTORY_SEPARATOR . $this->config['lang.path'];
        } else {
            $this->config['lang.path'] = dirname(dirname(dirname(dirname(__DIR__)))) . DIRECTORY_SEPARATOR . 'lang';
        }
    }

    /**
     * Get all language in specific folder
     *
     * @param  boolean $isLang If we want to get only the language list, return the list of language
     *                         if not, we return the list of language with it's line of keys
     *
     * @return array
     */
    protected function getLists($isLang = false)
    {
        $lists   = array();
        $path    = $this->config['lang.path'];
        $objects = new RII(new RDI($path), RII::SELF_FIRST);

        foreach ($objects as $object) {
            if ($object->getExtension() === 'php') {
                $pathName         = $object->getPathName();
                $explodedPathName = explode('/', dirname($pathName));
                $fileName         = end($explodedPathName);

                if (! $isLang) {
                    $lists[$fileName] = require_once $pathName;
                } else {
                    $lists[]          = $fileName;
                }
            }
        }

        return (! $isLang) ? $this->arrayDot($lists) : $lists;
    }

    /**
     * Get all language in specific folder
     *
     * @return array
     */
    public function getList()
    {
        return $this->getLists();
    }

    /**
     * Get list of language
     *
     * @return array List of language
     */
    public function getLangList()
    {
        return $this->getLists(false);
    }

    /**
     * Get absolute path of working application
     *
     * @return string
     */
    protected function getAppBasePath()
    {
        $path = dirname($_SERVER['SCRIPT_FILENAME']);

        return (is_link($path)) ? dirname(readlink($path)) : dirname($path);
    }
}
