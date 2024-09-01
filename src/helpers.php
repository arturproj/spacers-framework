<?php
/**
 * Get host location http(s)://host
 * @return string
 */
function get_host_location(): string
{
    return explode(
        "/",
        strtolower($_SERVER['SERVER_PROTOCOL'])
    )[0] . '://' . $_SERVER['HTTP_HOST'];
}

function dump($context, ...$values): void
{
    echo "<pre>";
    print_r($context);
    echo "</pre>";
    foreach ($values as $key => $value) {
        echo "<pre>";
        print_r($value);
        echo "</pre>";
    }
}