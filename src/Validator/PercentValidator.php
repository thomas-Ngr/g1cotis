<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PercentValidator extends ConstraintValidator
{
    public function validate($percent, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\Percent */

        if (!$constraint instanceof Percent) {
            throw new UnexpectedTypeException($constraint, Percent::class);
        }

        if (null === $percent || '' === $percent) {
            return;
        }

        if (!is_int($percent)) {
            throw new UnexpectedValueException($percent, 'int');
        }

        if ( $percent > 100 || $percent <= 0 ) {
            // the argument must be a string or an object implementing __toString()
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ percent }}', $percent)
                ->addViolation();
        }
    }
}
