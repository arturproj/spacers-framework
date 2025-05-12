<?php

namespace Spacers\Framework;

use Spacers\Framework\Component\Dotenv;
use Spacers\Framework\Constant\Attribute\Route;
use Spacers\Framework\Controller\AbstractController;
use Spacers\Framework\Exception\NotFoundExcetion;
use Spacers\Framework\Request\Request;
use Spacers\Framework\Response\FileResponse;

final class Kernel
{
    public static function init(callable|null $callback, array ...$extra)
    {
        Dotenv::load(get_default_environments(), Dotenv::LOAD_LIST);
        Dotenv::load(Dotenv::get("SPACERS_PROJECT_DIR") . '/.env', Dotenv::LOAD_FILE);

        is_callable($callback) && call_user_func(
            $callback,
            ...$extra
        );

        $controllers = self::loadControllerDir();

        if (empty($controllers)) {
            $default_path = realpath(__DIR__ . "/Controller/templates");
            $filename = $default_path . "/default.tpl.php";
            $response = new FileResponse($filename, [], [], 200);
            return AbstractController::getInstance()->flush_content_file_processing($response);
        }
        
        $current_route = new Route(
            path: parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH),
            alias: "client_request_route",
            method: $_SERVER["REQUEST_METHOD"]
        );

        foreach ($controllers as $ControllerClass) {
            /** @var \ReflectionClass $controller */
            $controller = self::getReflectedController($ControllerClass);
            ;

            foreach ($controller->getMethods(\ReflectionMethod::IS_PUBLIC) as $key => $action) {
                foreach ($action->getAttributes() as $key => $attribute) {

                    if (
                        $attribute->newInstance() instanceof Route
                        &&
                        $attribute->newInstance()->path === $current_route->path
                        &&
                        $attribute->newInstance()->method === $current_route->method
                    ) {
                        return $ControllerClass::getInstance()->{$action->name}(new Request(
                            url: $_SERVER["REQUEST_URI"],
                            method: $_SERVER["REQUEST_METHOD"],
                            content: file_get_contents("php://input"),
                            headers: apache_request_headers()
                        ));
                    }
                }
            }
        }

        throw new NotFoundExcetion("Requested route '{$current_route->method}:{$current_route->path}' unknown");
    }

    private static function loadControllerDir(): array
    {

        $SPACERS_PROJECT_DIR = Dotenv::get("SPACERS_PROJECT_DIR");
        $directory = new \RecursiveDirectoryIterator("$SPACERS_PROJECT_DIR/src/Controller");
        $iterator = new \RecursiveIteratorIterator($directory);
        $matches = new \RegexIterator($iterator, '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH);

        $controllers = array();
        foreach ($matches as $value) {
            $controllers[] = str_replace(
                // search string to replace
                ["$SPACERS_PROJECT_DIR/src", "/", ".php"],
                // with this
                ["\\App", "\\", ""],
                // string value
                $value[0]
            );
        }

        return $controllers;
    }

    /**
     * @param object::class|string
     * @return \ReflectionClass
     */
    private static function getReflectedController(object|string $controller)
    {
        try {
            return new \ReflectionClass($controller);
        } catch (\ReflectionException $th) {
            throw new \Exception($th->getMessage(), 0, $th);
        }
    }
}
