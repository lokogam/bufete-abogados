<?php

declare(strict_types=1);

/**
 * Bootstrap de PHPUnit.
 *
 * PHPUnit aplica las variables de phpunit.xml con putenv() antes de incluir
 * este archivo, pero no actualiza $_SERVER. El repositorio de entorno de
 * Laravel lee $_SERVER antes que $_ENV, por lo que las variables reales del
 * contenedor (docker-compose) o las heredadas del proceso artisan podrían
 * imponerse sobre la configuración de pruebas. Aquí se sincronizan ambas
 * fuentes para que phpunit.xml tenga siempre prioridad.
 */

require __DIR__.'/../vendor/autoload.php';

foreach (getenv() as $key => $value) {
    if ($value !== false) {
        $_SERVER[$key] = $value;
        $_ENV[$key] = $value;
    }
}