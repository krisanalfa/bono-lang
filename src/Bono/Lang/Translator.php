<?php namespace Bono\Lang;

use Bono\Lang\Contract\AbstractLangDriver;

/**
 * Translator class
 */
class Translator
{
    /**
     * Configuration
     *
     * @var array
     */
    protected $config = array();

    /**
     * Line of languages
     *
     * @var array
     */
    protected $list = array();

    /**
     * List of registered language
     *
     * @var array
     */
    protected $langList = array();

    /**
     * Driver to get list language
     *
     * @var AbstractLangDriver
     */
    protected $driver = null;

    /**
     * Language
     *
     * @var string
     */
    protected $lang = '';

    /**
     * Constructor
     *
     * @param array              $config Configuration
     * @param AbstractLangDriver $driver Driver to get list language
     */
    public function __construct(array $config, AbstractLangDriver $driver)
    {
        $this->driver   = $driver;
        $this->config   = $config;
        $this->list     = $driver->getList();
        $this->langList = $driver->getLangList();
        $this->setLanguage($config['lang']);
    }

    /**
     * Translate a language
     *
     * @param  string $key     The keyword
     * @param  array  $param   Parameters
     *
     * @return string          Translated word(s)
     */
    public function translate($key, $param = array())
    {

        $lang    = $this->lang;
        $default = ($this->isDebugMode()) ? '{{ '.$key.' }}' : null;

        if (! $this->hasLanguage($lang)) {
            $message = 'Language ['.$lang.'] is not available';

            throw new LangException($message);
        }

        $line = $this->arrayGet($lang.'.'.$key, $default);

        if (! empty($param)) {
            $line = $this->replaceParam($line, $param);
        }

        return $line;
    }

    /**
     * Get driver
     *
     * @return AbstractLangDriver
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * Set language
     *
     * @param string $lang Language we want to use
     */
    public function setLanguage($lang)
    {
        return $this->lang = $lang;
    }

    /**
     * Get active language
     *
     * @return string Language
     */
    public function getLanguage()
    {
        return $this->lang;
    }

    /**
     * Determine if we have sort kind of language in our repository
     *
     * @param  string  $lang Language to check
     *
     * @return boolean
     */
    public function hasLanguage($lang)
    {
        return isset($this->langList[$lang]);
    }

    /**
     * Find out if we are running in debug mode
     *
     * @return boolean
     */
    protected function isDebugMode()
    {
        return ($this->config['debug'] === true);
    }

    /**
     * Get line in dot notation
     *
     * @param  string $array   Keyword in dot.notation
     * @param  mixed  $default If we cannot find the keyword, return this default
     *
     * @return mixed
     */
    protected function arrayGet($key, $default = null)
    {
        if (isset($this->list[$key])) return $this->list[$key];

        foreach (explode('.', $key) as $segment) {
            if ( ! is_array($this->list) || ! array_key_exists($segment, $this->list)) {
                return value($default);
            }

            $this->list = $this->list[$segment];
        }

        return $this->list;
    }

    /**
     * Replace word from parameters
     *
     * @param  string $line  Word that has parameter mark to replace
     * @param  array  $param Parameters
     *
     * @return string
     */
    protected function replaceParam($line, $param)
    {
        foreach ($param as $key => $value) {
            $line = str_replace(':'.$key, $value, $line);
        }

        return $line;
    }
}
