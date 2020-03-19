<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Translator;

use Phalcon\Translate\Adapter;
use Phalcon\Translate\Adapter\NativeArray;


/**
 * Class Factory
 *
 * @package Bops\Translator
 */
class Factory {

    /**
     * @var Adapter[]
     */
    protected static $translators = [];

    /**
     * Creates the translator for a language
     *
     * @param string $language
     * @return Adapter
     */
    public static function factory(string $language = ''): Adapter {
        $language = self::extraLanguage($language);
        if (!isset(static::$translators[$language])) {
            /** @noinspection PhpIncludeInspection */
            static::$translators[$language] = new NativeArray([
                'content' => container('navigator')->localeDir("{$language}.php")
            ]);
        }
        return static::$translators[$language];
    }

    /**
     * Gets short name of the language
     *
     * @param string $language
     * @return string
     */
    private static function extraLanguage(string $language): string {
        if (strpos($language, '-') !== false) {
            return current(explode('-', $language));
        }

        if (empty($language)) {
            return _config('locale.default');
        }
        return $language;
    }

}
