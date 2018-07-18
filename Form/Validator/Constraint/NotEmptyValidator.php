<?php

namespace Artgris\Bundle\MediaBundle\Form\Validator\Constraint;

use Artgris\Bundle\MediaBundle\Entity\Media;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class NotEmptyValidator extends ConstraintValidator
{

    public function validate($value, Constraint $constraint)
    {
        if (null === $value) {
            return;
        }

        if (!$value instanceof Media) {
            throw new UnexpectedTypeException($value, Media::class);
        }

        if (empty($value->getPath())) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }

}