<?php

namespace Spacers\Framework\Constant\Pattern;

class Singleton
{
    /**
     * The actual singleton's instance almost always resides inside a static
     * field. In this case, the static field is an array, where each subclass of
     * the Singleton stores its own instance.
     */
    private static $_instances = [];
    /**
     * Singleton's constructor should not be public. However, it can't be
     * private either if we want to allow subclassing.
     */
    protected function __construct()
    {
    }

    // public function __destruct()
    // {
    //     dump("__destruct", $this::class, self::$_instances);
    //     unset(self::$_instances[array_search($this, self::$_instances, true)]);
    // }

    /**
     * Cloning and unserialization are not permitted for singletons.
     */
    protected function __clone()
    {
    }

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }

    /**
     * The method you use to get the Singleton's instance.
     */
    public static function getInstance(): object
    {
        $subclass = static::class;
        if (!isset(self::$_instances[$subclass])) {

            // Note that here we use the "static" keyword instead of the actual
            // class name. In this context, the "static" keyword means "the name
            // of the current class". That detail is important because when the
            // method is called on the subclass, we want an instance of that
            // subclass to be created here.

            self::$_instances[$subclass] = new static();
        }
        return self::$_instances[$subclass];
    }
    public function support($subclass): bool
    {
        return isset(self::$_instances[$subclass]);
    }
}
