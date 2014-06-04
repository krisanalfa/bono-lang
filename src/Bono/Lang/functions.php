<?php

use Bono\App;

if (! function_exists('t')) {
    /**
     * Alias to translate
     *
     * @param string $word    Word to translate
     * @param bool   $snake   Convert key to snake_case first
     * @param string $default If word doesn't registered in language list, then use the default
     *
     * @return string Translated word
     */
    function t($keyWord, $param = array())
    {
        return App::getInstance()->translator->translate($keyWord, $param);
    }
}
