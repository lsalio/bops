<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Translator;

use League\Flysystem\Filesystem;
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
            /* @var Filesystem $filesystem */
            $filesystem = container('filesystem', container('navigator')->localeDir());
            if (!$filesystem->has("{$language}.php")) {
                $language = env('SERVICE_TRANSLATOR_FALLBACK', 'en');
            }

            /** @noinspection PhpIncludeInspection */
            static::$translators[$language] = new NativeArray([
                'content' => include container('navigator')->localeDir("{$language}.php")
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
            return env('SERVICE_TRANSLATOR_DEFAULT');
        }
        return $language;
    }

}
