<?php

namespace Spacers\Framework\Controller;

interface AbstractControllerInterface
{
    /**
     * Summary of getInstance
     * @return void
     */
    public static function getInstance(): object;
    /**
     * Summary of support
     * @param string $subclass
     * @return void
     */
    public function support(string $subclass): bool;
}