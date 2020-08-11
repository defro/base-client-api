<?php
namespace fGalvao\BaseClientApi;

use JsonSerializable;

interface ResourceInterface extends JsonSerializable
{
    /**
     * @param array $properties
     *
     * @return mixed
     */
    public static function hydrate(array $properties);

}