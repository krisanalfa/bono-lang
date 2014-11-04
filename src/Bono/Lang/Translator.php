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
        $this->list     = $driver->getLists();
        $this->langList = $driver->getLangLists();

        $this->setLanguage($config['lang']);
    }

    /**
     * Translate a language
     *
     * @param string $key   The keyword
     * @param array  $param Parameters
     *
     * @return string Translated word(s)
     */
    public function translate($key, $param = array(), $defaultLine = '')
    {
        $line = $this->replaceParam($this->getLine($key, $defaultLine), $param);

        if (! $line) {
            return ($this->isDebugMode()) ? '{{ '.$key.' }}' : $line;
        }

        return $line;
    }

    /**
     * Retrieve a translation string that is different depending on a count.
     *
     * @param string $key   Word to translate
     * @param array  $count Which one should translate take for current choice?
     * @param string $param Parameter / placeholder that would be attached to the line
     *
     * @return string Translated word
     */
    public function choice($key, $count = 1, $param = array())
    {
        $line = $this->getLine($key);

        $lines = explode('|', $line);

        if (isset($lines[$count - 1])) {
            $line = $lines[$count - 1];

            $param['count'] = $count;

            return $this->replaceParam($line, $param);
        }

        return ($this->isDebugMode()) ? '{{ '.$key.' }}' : $line;
    }

    /**
     * Get raw translated line
     *
     * @param string $key Word to translate
     *
     * @return mixed Translated word
     */
    protected function getLine($key, $default = null)
    {
        $lang    = $this->lang;
        $key     = $this->sanitize($key);

        if (! $this->hasLanguage($lang)) {
            $message = 'Language ['.$lang.'] is not available';

            throw new LangException($message);
        }

        $line = $this->arrayGet($lang.'.'.$key, $default);

        return $line;
    }

    /**
     * Sanitize key, removing spaces and replace them to underscore
     *
     * @param string $key
     *
     * @return string
     */
    protected function sanitize($key)
    {
        return strtolower(str_replace(' ', '_', $key));
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
     * Determine if we have sort kind of language in our repository
     *
     * @param string $lang Language to check
     *
     * @return boolean
     */
    public function hasLanguage($lang)
    {
        return isset($this->langList[$lang]);
    }

    /**
     * Get line in dot notation
     *
     * @param string $array   Keyword in dot.notation
     * @param mixed  $default If we cannot find the keyword, return this default
     *
     * @return mixed
     */
    protected function arrayGet($key, $default = null)
    {
        if (isset($this->list[$key])) {
            return $this->list[$key];
        }

        foreach (explode('.', $key) as $segment) {
            if (! is_array($this->list) || ! array_key_exists($segment, $this->list)) {
                return (is_callable($default)) ? $default() : $default;
            }

            $this->list = $this->list[$segment];
        }

        return $this->list;
    }

    /**
     * Replace word from parameters
     *
     * @param string $line  Word that has parameter mark to replace
     * @param array  $param Parameters
     *
     * @return string
     */
    protected function replaceParam($line, array $param)
    {
        if (! empty($param) and is_array($param)) {
            foreach ($param as $key => $value) {
                $line = str_replace(':'.$key, $value, $line);
            }
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
        $this->lang = $lang;
    }

    /**
     * Get current active language
     *
     * @return string Language
     */
    public function getLanguage()
    {
        return $this->lang;
    }
}
