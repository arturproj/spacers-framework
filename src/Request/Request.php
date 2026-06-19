<?php

namespace Spacers\Framework\Request;

class Request
{
    protected string $path;
    protected mixed $parameters = [];

    public function __construct(
        string $url,
        protected string $method,
        protected ?string $content = "",
        protected array $headers = []
    ) {
        $request = parse_url($url);

        $this->path = $request["path"];

        if (key_exists("query", $request)) {
            parse_str($request["query"], $parsed_query);
            $this->parameters["query"] = $parsed_query;
        }

        $this->parameters["body"] = array_merge(json_decode($content, true) ?? [], $_POST);

    }
    public function getContent(): string
    {
        return $this->content;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }
}
