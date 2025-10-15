<?php

namespace ShopFlow\Helpers;

class StringHelper
{
    public static function slugify(string $text): string
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);
        
        return empty($text) ? 'n-a' : $text;
    }

    public static function truncate(string $text, int $length = 100, string $suffix = '...'): string
    {
        if (mb_strlen($text) <= $length) {
            return $text;
        }
        
        return mb_substr($text, 0, $length) . $suffix;
    }

    public static function sanitize(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }

    public static function generateRandomString(int $length = 32): string
    {
        return bin2hex(random_bytes($length / 2));
    }

    public static function camelToSnake(string $input): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }

    public static function snakeToCamel(string $input): string
    {
        return lcfirst(str_replace('_', '', ucwords($input, '_')));
    }
}
