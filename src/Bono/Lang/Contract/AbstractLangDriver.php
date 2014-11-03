<?php namespace Bono\Lang\Contract;

/**
 * Base contract
 */
abstract class AbstractLangDriver
{
    /**
     * Get list of available translation in our dictionary
     *
     * @return array
     */
    abstract public function getLists();

    /**
     * Get list of avaliable language
     *
     * @return array
     */
    abstract public function getLangLists();

    /**
     * Build an array dot notation
     *
     * @param array  $array   Array we want to convert to dot notation array
     * @param string $prepend Prepend string to master key
     *
     * @return array
     */
    protected function arrayDot($array, $prepend = '')
    {
        $results = array();

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $results = array_merge($results, $this->arrayDot($value, $prepend.$key.'.'));
            } else {
                $results[$prepend.$key] = $value;
            }
        }

        return $results;
    }
}
