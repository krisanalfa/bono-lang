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
     * List of available words in dictionary
     *
     * @var array
     */
    protected $list = array();

    /**
     * List of available language
     *
     * @var array
     */
    protected $langList = array();

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

        $this->buildLists();
    }

    /**
     * Get list of available translation in our dictionary
     *
     * @return array
     */
    public function getLists()
    {
        return $this->list;
    }

    /**
     * Get list of avaliable language
     *
     * @return array
     */
    public function getLangLists()
    {
        return $this->langList;
    }

    /**
     * Build list of translation and available language in specific folder
     *
     * @return void
     */
    protected function buildLists()
    {
        $lists    = array();
        $langList = array();
        $path     = $this->config['lang.path'];
        $objects  = new RII(new RDI($path), RII::SELF_FIRST);

        foreach ($objects as $object) {
            if ($object->getExtension() === 'php') {
                $pathName         = $object->getPathName();
                $explodedPathName = explode('/', dirname($pathName));
                $fileName         = end($explodedPathName);

                $langList[$fileName] = true;
                $lists[$fileName] = require_once $pathName;
            }
        }

        $this->langList = $langList;
        $this->list = $this->arrayDot($lists);
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
