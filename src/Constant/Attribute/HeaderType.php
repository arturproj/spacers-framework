<?php
namespace Spacers\Framework\Constant\Attribute;

abstract class AbstractType
{
    public function __get($key)
    {
        return $this->$key;
    }
}

#[\Attribute]
class HeaderType extends AbstractType
{
    /**
     * Summary of __construct
     * @param string $name
     * @param string $value
     */
    public function __construct(
        protected string $name,
        protected string $value
    ) {
    }
}