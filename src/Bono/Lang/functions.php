<?php

use Bono\App;

if (! function_exists('t')) {
    /**
     * Alias to translate
     *
     * @param string $word    Word to translate
     * @param array  $param   Parameter / placeholder that would be attached to the line
     * @param string $default If word doesn't registered in language list, then use the default
     *
     * @return string Translated word
     */
    function t($keyWord, $param = array(), $default = '')
    {
        $translator = App::getInstance()->translator;

        if (is_null($translator)) {
            throw new Exception('Undefined translator, please register LangProvider.');
        }

        return $translator->translate($keyWord, $param, $default);
    }
}

if (! function_exists('c')) {
    /**
     * Alias to choice
     *
     * @param string $word  Word to translate
     * @param array  $count Which one should translate take for current choice?
     * @param string $param Parameter / placeholder that would be attached to the line
     *
     * @return string Translated word
     */
    function c($keyWord, $count = 1, $param = array())
    {
        $translator = App::getInstance()->translator;

        if (is_null($translator)) {
            throw new Exception('Undefined translator, please register LangProvider.');
        }

        return $translator->choice($keyWord, $count, $param);
    }
}
