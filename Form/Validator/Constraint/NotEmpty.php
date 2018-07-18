<?php

namespace Artgris\Bundle\MediaBundle\Form\Validator\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @Annotation
 */
class NotEmpty extends Constraint
{

    public $message = 'artgris_validation.not_blank';
}