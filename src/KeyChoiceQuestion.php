<?php

namespace Nickfairchild\Wpise;

use Symfony\Component\Console\Question\ChoiceQuestion;

class KeyChoiceQuestion extends ChoiceQuestion
{
    protected function isAssoc(array $array): bool
    {
        return true;
    }
}
