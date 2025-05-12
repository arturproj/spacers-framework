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
        // dump($url, $method, $request);

        $this->path = $request["path"];

        if (key_exists("query", $request)) {
            parse_str($request["query"], $parsed_query);
            $this->parameters["query"] = $parsed_query;
        }
        if ($content && json_validate($content)) {
            $this->parameters["request"] = json_decode($content, true);
        }
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

    public function toArray(): array
    {
        if (json_validate($this->content)) {
            return json_decode($this->content, true);
        }

        return [];
    }
}
