<?php
namespace fGalvao\BaseClientApi;

interface ResourceInterface
{
    /**
     * @param array $properties
     *
     * @return mixed
     */
    public static function hydrate(array $properties);

}