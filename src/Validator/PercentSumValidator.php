<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\Common\Collections\ArrayCollection;

class PercentSumValidator extends ConstraintValidator
{
    public function validate($recipients, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\PercentSum */

        if (null === $recipients || '' === $recipients) {
            return;
        }

        $sum = 0;
        foreach ($recipients as $recipient) {
            $sum += $recipient->getPercent();
        }

        if ( $sum > 1 ) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ sum }}', $sum)
                ->addViolation();
        }
    }
}
