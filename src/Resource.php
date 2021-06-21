<?php
namespace fGalvao\BaseClientApi;

use InvalidArgumentException;

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
        }
    
        $objs = [];
        foreach ($properties as $property) {
            $objs[] = (new static($property));
        }
        return $objs;
    
    }
    
    /**
     * @param array $properties
     *
     * @return ResourceInterface
     */
    protected function _hydrate(array $properties)
    {
        if ($this->rootKey && array_key_exists($this->rootKey, $properties)) {
            return self::hydrate($properties[$this->rootKey]);
        }
    
        foreach ($properties as $key => $value) {
            /*$keyCamelCase = lcfirst(
                str_replace(' ', '',
                    ucwords(
                        str_replace(['-', '_'], ' ', $key)
                    )
                )
            );
            
            $key = $keyCamelCase;
            */
        
            $key = lcfirst($key);
        
            if (array_key_exists($key, $this->map)) {
                $key = $this->map[$key];
            }
        
            if (in_array($key, $this->ignore, true)) {
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
                    throw new InvalidArgumentException($error);
                }
            
                $value = $value ? (new $class())->hydrate($value) : null;
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
    public function __get(string $name)
    {
        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }
        
        return null;
    }
    
    /**
     * @param string $name
     * @param        $value
     */
    public function __set(string $name, $value): void
    {
        $this->attributes[$name] = $value;
    }
    
    /**
     * @param string $name
     *
     * @return bool
     */
    public function __isset(string $name): bool
    {
        return isset($this->attributes[$name]);
    }
    
    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
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
    public function toJson(int $options = 0)
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