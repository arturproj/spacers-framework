<?php

namespace Spacers\Framework\Constant\Attribute;

#[\Attribute]
class Route
{
    /**
     * Summary of __construct
     * @param string $path
     * @param string $alias
     * @param string|array $method
     */
    public function __construct(
        public string $path,
        public string $alias = "action",
        public string|array $method = ["GET","POST","PUT","UPDATE","DELETE","OPTION"]
    ) {
    }
}