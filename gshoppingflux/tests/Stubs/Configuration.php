<?php

/**
 * Minimal stand-in for PrestaShop's Configuration class: get() reads from
 * an in-memory array a test fills via self::$values (or updateValue()),
 * and returns false for a key that was never stored, like the real one.
 */
class Configuration
{
    /** @var array<string, mixed> */
    public static $values = [];

    /** A NULL value reads as missing, like the real one (isset() based cache) */
    public static function get($key, $id_lang = null, $id_shop_group = null, $id_shop = null, $default = false)
    {
        $value = isset(self::$values[$key]) ? self::$values[$key] : $default;

        return is_array($value) ? (isset($value[$id_lang]) ? $value[$id_lang] : $default) : $value;
    }

    /**
     * Stores the value the way PrestaShop does: as a string, except that
     * an empty string (so false too) is written as NULL in the database.
     */
    public static function updateValue($key, $values, $html = false, $id_shop_group = null, $id_shop = null)
    {
        if (is_array($values)) {
            self::$values[$key] = $values;
        } else {
            self::$values[$key] = (string) $values === '' ? null : (string) $values;
        }

        return true;
    }
}
