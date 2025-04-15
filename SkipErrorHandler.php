<?php declare(strict_types = 1);

/*
 * This file is part of the Vairogs package.
 *
 * (c) Dāvis Zālītis (k0d3r1s) <davis@vairogs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vairogs\Functions\Preg;

use RuntimeException;

use function restore_error_handler;
use function set_error_handler;

final class SkipErrorHandler
{
    private function __construct()
    {
    }

    public static function execute(
        callable $callback,
    ) {
        $error = null;

        set_error_handler(static function (int $errorNumber, string $errorString) use (&$error): bool {
            $error = $errorString;

            return true;
        });

        try {
            $result = $callback();
        } finally {
            restore_error_handler();
        }

        if (null !== $error) {
            throw new RuntimeException($error);
        }

        return $result;
    }
}
