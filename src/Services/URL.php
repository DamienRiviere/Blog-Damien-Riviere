<?php

namespace App\Services;

class URL
{
    /**
     * Check if the number in params is an int
     *
     * @param string $name
     * @param int|null $default
     * @return int|null
     * @throws \Exception
     */
    public static function getParamInt(string $name, ?int $default = null): ?int
    {
        if (!isset($_GET[$name])) {
            return $default;
        }

        if (!filter_var($_GET[$name], FILTER_VALIDATE_INT)) {
            throw new \Exception("Le paramètre $name dans l'url n'est pas un entier");
        }

        return (int)$_GET[$name];
    }

    /**
     * Check if the int is positive
     *
     * @param string $name
     * @param int|null $default
     * @return int|null
     * @throws \Exception
     */
    public static function getPositiveInt(string $name, ?int $default = null): ?int
    {
        $param = self::getParamInt($name, $default);
        if ($param !== null && $param <= 0) {
            throw new \Exception("Le paramètre $name dans l'url n'est pas un entier positif");
        }

        return $param;
    }
}
