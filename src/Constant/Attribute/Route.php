<?php

namespace Spacers\Framework\Constant\Attribute;

#[\Attribute]
class Route
{
    /**
     * Summary of __construct
     * @param string $path
     * @param string $alias
     * @param string $method
     */
    public function __construct(
        public string $path,
        public string $alias,
        public string $method
    ) {
    }
}