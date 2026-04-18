<?php

/**
 * Local dev helper: prints admin credentials and runs `php artisan serve`.
 * Použitie: composer dev   (alebo: php scripts/dev-serve.php)
 */
declare(strict_types=1);

$root = dirname(__DIR__);
chdir($root);

echo PHP_EOL;
echo '══════════════════════════════════════════════════════'.PHP_EOL;
echo '  RackRush – administrátor (DatabaseSeeder)'.PHP_EOL;
echo '  E-mail:  admin@rackrush.test'.PHP_EOL;
echo '  Heslo:   password'.PHP_EOL;
echo '══════════════════════════════════════════════════════'.PHP_EOL;
echo PHP_EOL;

// Symlink public/storage -> storage/app/public (uploady sa bez toho nezobrazia).
passthru(PHP_BINARY.' artisan storage:link');

echo PHP_EOL.'Spúšťam: php artisan serve'.PHP_EOL.PHP_EOL;

$argv = $_SERVER['argv'] ?? [];
$extra = array_slice($argv, 1);
$escaped = array_map(static fn (string $a): string => escapeshellarg($a), $extra);
$cmd = PHP_BINARY.' artisan serve '.implode(' ', $escaped);

passthru($cmd, $exitCode);
exit($exitCode);
