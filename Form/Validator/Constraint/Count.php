<?php

namespace Artgris\Bundle\MediaBundle\Form\Validator\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\MissingOptionsException;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class Count extends \Symfony\Component\Validator\Constraints\Count
{

}
