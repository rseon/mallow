<?php

namespace Rseon\Mallow;

class Locale
{
    const DEFAULT_SECTION = 'global';

    protected $locale;
    protected $translations = [];

    protected static $instance;
    public static function getInstance()
    {
        if(!(static::$instance instanceof static)) {
            static::$instance = new static;
        }

        return static::$instance;
    }


    /**
     * Set the locale and retrieve translations
     *
     * @param string $locale
     * @return $this
     * @throws Exceptions\AppException
     */
    public function setLocale(string $locale)
    {
        $this->locale = $locale;

        if(!array_key_exists($locale, $this->translations)) {
            $file = get_path(config('langs_path')."/$locale.php");
            if(file_exists($file)) {
                $this->translations[$locale]['global'] = require $file;
            }

            $folder = get_path(config('langs_path')."/$locale");
            if(is_dir($folder)) {
                foreach (glob($folder."/*.php") as $file) {
                    $this->translations[$locale][pathinfo($file, PATHINFO_FILENAME)] = require $file;
                }
            }
        }

        return $this;
    }

    /**
     * Get current locale
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Translate a string with its parameters from $section.
     *
     * @param string $string
     * @param array $params
     * @param string|null $section
     * @param string|null $locale
     * @return mixed|string|string[]
     */
    public function translate(string $string, array $params = [], string $section = null, string $locale = null)
    {
        if(!$locale) {
            $locale = $this->getLocale();
        }
        if(!$section) {
            $section = static::DEFAULT_SECTION;
        }

        $string = $this->translations[$locale][$section][$string] ?? $string;

        foreach($params as $k => $v) {
            $key = ":$k";
            $string = str_replace($key, $v, $string);
        }

        return $string;
    }

    public function exists($locale, $section, $string)
    {
        return array_key_exists($locale, $this->translations)
            && array_key_exists($section, $this->translations[$locale])
            && array_key_exists($string, $this->translations[$locale][$section]);
    }
}