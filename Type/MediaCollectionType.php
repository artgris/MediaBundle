<?php

namespace Artgris\Bundle\MediaBundle\Type;


use Artgris\Bundle\MediaBundle\Entity\Media;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonArrayType;


class MediaCollectionType extends JsonArrayType
{
    const TYPE = 'media_collection';

    public function getName()
    {
        return self::TYPE;
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (is_array($value)) {
            $value = new ArrayCollection($value);
        }

        /** @var ArrayCollection|Media[] $value */
        foreach ($value as $item) {
            if ($item === null || $item->getPath() === null) {
                $value->removeElement($item);
            }
        }

        return json_encode($value->toArray());
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (empty($value)) {
            return null;
        }

        $value = (is_resource($value)) ? stream_get_contents($value) : $value;

        $json = json_decode($value, true);

        $res = new ArrayCollection();
        foreach ($json as $el) {
            $image = new Media();

            if (isset($el['alt'])) {
                $image->setAlt($el['alt']);
            }

            if (isset($el['path'])) {
                $image->setPath($el['path']);
            }

            $res->add($image);
        }

        if ($res->isEmpty()) {
            $res->add(new Media());
        }

        return $res;
    }

}
