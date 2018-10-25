<?php

namespace Artgris\Bundle\MediaBundle\Form\Validator\Constraint;

use Artgris\Bundle\MediaBundle\Entity\Media;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CountValidator extends \Symfony\Component\Validator\Constraints\CountValidator
{

    public function validate($value, Constraint $constraint)
    {
        if ($value instanceof Collection) {
            $value = array_filter($value->toArray(), function ($path) {
                return $path !== null;
            });
        }

        parent::validate($value, $constraint);
    }

}
