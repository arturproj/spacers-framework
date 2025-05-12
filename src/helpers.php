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

/**
 * Summary of spacers_exception_handler
 * @param Throwable $exception
 * @return void
 */
function spacers_exception_handler(Throwable $exception): void
{
    dump($exception);
}

/**
 * Summary of default_environments
 * @param array $environments
 * @return array
 */
function get_default_environments(array $environments = []): array
{
    $environments["SPACERS_PROJECT_DIR"] = realpath(getcwd() . "/../");
    $environments["APP_DEBUG"] = isset($environments["APP_DEBUG"]) ? ((bool) $environments["APP_DEBUG"]) : false;
    $environments["APP_ENV"] = isset($environments["APP_ENV"]) ? ((bool) $environments["APP_ENV"]) : "production";
    return $environments;
}
/**
 * Summary of is_debug
 * @return bool
 */
function is_debug(): bool
{
    return (bool) getenv("APP_DEBUG", false);
}

/**
 * Build template with attributes vars
 * @param string $fimename template filename (index.tpl.php)
 * @param array $attributes 
 * @return string
 */
function render_template(string $fimename, array $attributes = []): string
{

    ob_start();
    foreach ($attributes as $key => $value) {
        $$key = $value;
    }
    require $fimename;
    $content = ob_get_clean();
    ob_end_flush();
    return $content;
}
