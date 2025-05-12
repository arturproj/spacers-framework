<?php

namespace Spacers\Framework\Component;

use Spacers\Framework\Constant\Pattern\Singleton;

class Dotenv extends Singleton
{
    protected static array $environments = [];
    const LOAD_FILE = 0;
    const LOAD_LIST = 1;
    /**
     * Summary of load
     * @param string|array $env
     * @param int $mode
     * @return void
     */
    public static function load(string|array $env, int $mode = 0): void
    {
        switch ($mode) {
            case self::LOAD_FILE:
                self::load_file($env);
                break;
            case self::LOAD_LIST:
                self::load_list($env);
                break;
            default:
                break;
        }
    }
    /**
     * Summary of load_file
     * @param string $filename
     * @return void
     */
    private static function load_file(string $filename): void
    {
        if (file_exists($filename) && file_get_contents($filename)) {
            foreach (explode("\n", file_get_contents($filename)) as $value) {

                [$key, $value] = explode("=", trim($value));
                if (!str_starts_with($key, "#")) {

                    if (filter_var($value, FILTER_VALIDATE_INT)) {
                        $value = filter_var(
                            $value,
                            FILTER_VALIDATE_INT,
                            FILTER_SANITIZE_NUMBER_INT
                        );
                    } elseif (filter_var($value, FILTER_VALIDATE_FLOAT)) {
                        $value = filter_var(
                            $value,
                            FILTER_VALIDATE_FLOAT,
                            FILTER_SANITIZE_NUMBER_FLOAT
                        );
                    } elseif (filter_var($value, FILTER_VALIDATE_BOOL)) {
                        $value = filter_var(
                            $value,
                            FILTER_VALIDATE_BOOL,
                            FILTER_NULL_ON_FAILURE
                        );
                    } else {
                    }

                    self::set(
                        trim($key),
                        $value
                    );
                }
            }
        }
    }
    /**
     * Summary of load_list
     * @param array $environments
     * @return void
     */
    private static function load_list(array $environments): void
    {
        foreach ($environments as $key => $value) {
            self::set(trim($key), trim($value));
        }
    }
    /**
     * Summary of all
     * @return array
     */
    public static function all(): array
    {
        return self::$environments;
    }
    /**
     * Summary of get
     * @param string $key
     * @param mixed $default
     */
    public static function get(string $key, $default = null): ?string
    {

        $value = getenv($key);
        if (filter_var($value, FILTER_VALIDATE_INT)) {
            $value = filter_var(
                $value,
                FILTER_VALIDATE_INT,
                FILTER_SANITIZE_NUMBER_INT
            );
        } elseif (filter_var($value, FILTER_VALIDATE_FLOAT)) {
            $value = filter_var(
                $value,
                FILTER_VALIDATE_FLOAT,
                FILTER_SANITIZE_NUMBER_FLOAT
            );
        } elseif (filter_var($value, FILTER_VALIDATE_BOOL)) {
            $value = filter_var(
                $value,
                FILTER_VALIDATE_BOOL,
                FILTER_NULL_ON_FAILURE
            );
        } else {
        }

        if ($value != $default) {
            return $value;
        }
        return $default;
    }
    /**
     * Summary of set
     * @param string $key
     * @param string $value
     * @return bool
     */
    public static function set(string $key, string $value = ""): bool
    {
        self::$environments[$key] = $value;
        return putenv($key . "=" . $value);
    }
}
