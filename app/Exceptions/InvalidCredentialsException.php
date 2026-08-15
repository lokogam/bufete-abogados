<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

/**
 * Se lanza cuando las credenciales de acceso no son válidas.
 */
final class InvalidCredentialsException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Las credenciales proporcionadas no son válidas.');
    }
}
