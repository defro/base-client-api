<?php
namespace fGalvao\BaseClientApi;

abstract class Resource implements ResourceInterface
{
    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @var array
     */
    protected $cast = [];

    /**
     * Map the api response field for a different name
     *
     * @var array
     */
    protected $map = [];

    /** @var array */
    protected $ignore = [];

    /** @var string|null */
    protected $rootKey = null;

    /**
     * Order constructor.
     *
     * @param array|null $properties
     */
    public function __construct(array $properties = null)
    {
        if ($properties) {
            $this->_hydrate($properties);
        }
    }

    /**
     * @param array $properties
     *
     * @return array|Resource|ResourceInterface|mixed
     */
    public static function hydrate(array $properties)
    {
        $keys    = array_keys($properties);
        $isAssoc = array_keys($keys) !== $keys;
        if ($isAssoc) {
            $obj = new static();
            return $obj->_hydrate($properties);
        } else {
            $objs = [];
            foreach ($properties as $property) {
                $objs[] = (new static($property));
            }
            return $objs;
        }

    }

    /**
     * @param array $properties
     *
     * @return ResourceInterface
     */
    protected function _hydrate(array $properties)
    {
        if ($this->rootKey && array_key_exists($this->rootKey, $properties)) {
            return $this->hydrate($properties[$this->rootKey]);
        }

        foreach ($properties as $key => $value) {
            $keyCamelCase = lcfirst(
                str_replace(' ', '',
                    ucwords(
                        str_replace(['-', '_'], ' ', $key)
                    )
                )
            );

            $key = $keyCamelCase;

            if (array_key_exists($key, $this->map)) {
                $key = $this->map[$key];
            }

            if (in_array($key, $this->ignore)) {
                continue;
            }

            if (array_key_exists($key, $this->cast)) {
                $class = $this->cast[$key];
                if (!class_exists($class)) {
                    $error = sprintf(
                        'Error on cast the property [%s] from [%s]. The class [%s] does not exist.',
                        $key,
                        get_class($this),
                        $class
                    );
                    throw new \InvalidArgumentException($error);
                }

                $value = (new $class())->hydrate($value);
            }


            $this->setAttribute($key, $value);
        }

        return $this;
    }

    /**
     * @param $key
     * @param $value
     */
    protected function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * is utilized for reading data from inaccessible members.
     *
     * @param $name string
     *
     * @return mixed
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }

        return null;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->attributes;
    }


    /**
     * @param int $options
     *
     * @return false|string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->attributes, $options);
    }

    /**
     * This method is called by var_dump() when dumping an object to get the properties that should be shown.
     * If the method isn't defined on an object, then all public, protected and private properties will be shown.
     *
     * @return array
     * @since PHP 5.6.0
     *
     * @link  https://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.debuginfo
     */
    public function __debugInfo()
    {
        return $this->attributes;
    }
}