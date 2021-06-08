<?php

namespace Artgris\Bundle\MediaBundle\Form\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
#[\Attribute]
class Image extends Constraint
{
    public $message = 'artgris_validation.image';
}
