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

        if (!$value instanceof Media) {
            throw new UnexpectedTypeException($value, Media::class);
        }

        if (empty($value->getPath())) {
            return;
        }

        $extension = strtolower(pathinfo($value->getPath(), PATHINFO_EXTENSION));

        if (!in_array($extension, self::SUPPORTED_EXTENSIONS)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }

}