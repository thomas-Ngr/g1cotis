<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PercentSum extends Constraint
{
    public $message = 'The percents sum "{{ sum }}" is superior to 100.';
}
