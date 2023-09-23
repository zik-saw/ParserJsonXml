<?php

namespace App\Services;

class Helpers
{
    /**
     * Провеярет, что строка соотвествует формату json
     * @param string $str
     * @return bool
     */
    public function isJson(string $str): bool
    {
        json_decode($str);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Проверяет, что строка соотвесвтует формату xml
     * @param string $str
     * @return bool
     */
    public function isXml(string $str): bool
    {
        $prev = libxml_use_internal_errors(true);

        $doc = simplexml_load_string($str);
        $errors = libxml_get_errors();

        libxml_clear_errors();
        libxml_use_internal_errors($prev);

        return false !== $doc && empty($errors);
    }
}
