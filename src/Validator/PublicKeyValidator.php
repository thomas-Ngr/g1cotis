<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class PublicKeyValidator extends ConstraintValidator
{

    //private static $substrate_b58_regex = '/^[123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz]{0,48}$/';
    private static $substrate_b58_regex = '/^[123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz]{47,48}$/';

    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\PublicKey */

        if (!$constraint instanceof PublicKey) {
            throw new UnexpectedTypeException($constraint, PublicKey::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        //dump($value);
        //die();

        if ( preg_match(self::$substrate_b58_regex, $value, $matches) !== 1 ) {
            // the argument must be a string or an object implementing __toString()
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}
