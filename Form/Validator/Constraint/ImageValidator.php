<?php

namespace Artgris\Bundle\MediaBundle\Form\Validator\Constraint;

use Artgris\Bundle\MediaBundle\Entity\Media;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ImageValidator extends ConstraintValidator
{

    private const SUPPORTED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'svg'];
    
    public function validate($value, Constraint $constraint)
    {
        if (null === $value) {
            return;
        }

        $extension = strtolower(pathinfo($value, PATHINFO_EXTENSION));

        if (!in_array($extension, self::SUPPORTED_EXTENSIONS)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }

}