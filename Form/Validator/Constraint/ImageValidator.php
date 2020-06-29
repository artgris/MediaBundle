<?php

namespace Artgris\Bundle\MediaBundle\Form\Validator\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ImageValidator extends ConstraintValidator
{
    private const SUPPORTED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'svg'];

    public function validate($value, Constraint $constraint)
    {
        if (null === $value) {
            return;
        }

        if (is_iterable($value)) {
            foreach ($value as $item) {
                $this->checkExtension($constraint, $item);
            }
        } else {
            $this->checkExtension($constraint, $value);
        }
    }

    private function checkExtension(Constraint $constraint, string $path): void
    {
        if ($path = parse_url($path)) {
            $extension = mb_strtolower(pathinfo($path['path'], PATHINFO_EXTENSION));
        }

        if (!\in_array($extension, self::SUPPORTED_EXTENSIONS)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
