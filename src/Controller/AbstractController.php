<?php

namespace Spacers\Framework\Controller;

use Spacers\Framework\Component\Dotenv;
use Spacers\Framework\Constant\Attribute\File;
use Spacers\Framework\Constant\Attribute\HeaderType;
use Spacers\Framework\Constant\Pattern\Singleton;
use Spacers\Framework\Exception\NotFoundExcetion;
use Spacers\Framework\Response\FileResponse;
use Spacers\Framework\Response\JsonResponse;
use Spacers\Framework\Response\Response;

class AbstractController extends Singleton implements AbstractControllerInterface
{
    /**
     * Summary of text
     * @param string $text
     * @param array $headers
     * @param int $code
     * @return Response
     */
    public function text(string $text, array $headers = [], int $code = 200): Response
    {
        $response = new Response($text, $headers, $code);
        $this->flush_content_file_processing($response);
        return $response;
    }

    /**
     * Summary of json
     * @param mixed $data
     * @param array $headers
     * @param int $code
     * @return Response
     */
    public function json($data, array $headers = [], int $code = 200): Response
    {
        $response = new JsonResponse($data, $headers, $code);
        $this->flush_content_file_processing($response);
        return $response;
    }

    /**
     * Summary of render
     * @param string $filename
     * @param array $proprieties
     * @param array $headers
     * @param int $code
     * @throws NotFoundExcetion
     * @return Response
     */
    public function render(string $filename, array $proprieties = [], array $headers = [], int $code = 200): Response
    {
        $template_path = Dotenv::get("SPACERS_PROJECT_DIR") . "/src/View";

        if (!is_dir($template_path)) {
            throw new NotFoundExcetion("Template directory '$template_path' not found.");
        }
        $filename = $template_path . "/" . $filename;
        if (!is_file($filename)) {
            throw new NotFoundExcetion("Template '$filename' not found.");
        }

        $response = new FileResponse($filename, $proprieties, $headers, $code);
        $this->flush_content_file_processing($response);
        return $response;
    }

    /**
     * Summary of flush_content_file_processing
     * @param Response $response
     */
    public function flush_content_file_processing(Response $response)
    {
        if (!headers_sent()) {
            header("HTTP/1.1 $response->code");
            foreach ($response->headers as $header) {
                header("$header->name: $header->value");
            }
        }

        echo $response->content;
    }
}