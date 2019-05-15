<?php

namespace Artgris\Bundle\MediaBundle\Form\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Image extends Constraint
{
    public $message = 'artgris_validation.image';
}
