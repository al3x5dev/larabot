<?php

namespace Mk4U\LaraBot\Commands;

trait ValidatesName
{
    protected function validateName(string $name): bool
    {
        return (bool) preg_match('/^[A-Za-z][A-Za-z0-9\/_-]*$/', $name);
    }
}
