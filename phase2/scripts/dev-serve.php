<?php

/**
 * Local dev helper: prints admin credentials and runs `php artisan serve`.
 * Usage: composer dev (or: php scripts/dev-serve.php)
 */
declare(strict_types=1);

$root = dirname(__DIR__);
chdir($root);

echo PHP_EOL;
echo '══════════════════════════════════════════════════════'.PHP_EOL;
echo '  RackRush – admin (DatabaseSeeder)'.PHP_EOL;
echo '  E-mail:  admin@rackrush.test'.PHP_EOL;
echo '  Password: password'.PHP_EOL;
echo '══════════════════════════════════════════════════════'.PHP_EOL;
echo PHP_EOL;

// Symlink / junction: public/storage -> storage/app/public (without it, uploaded files 404).
// On Windows this is often a directory junction; Laravel may not treat it as a symlink, so artisan can error.
// If the target already exists, skip linking (on a fresh clone without public/storage, run: php artisan storage:link).
$publicStorage = $root.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'storage';
if (file_exists($publicStorage)) {
    echo '[dev] public/storage already exists — skipping storage:link.'.PHP_EOL;
} else {
    passthru(PHP_BINARY.' artisan storage:link --force');
}

echo PHP_EOL.'Starting: php artisan serve'.PHP_EOL.PHP_EOL;

$argv = $_SERVER['argv'] ?? [];
$extra = array_slice($argv, 1);
$escaped = array_map(static fn (string $a): string => escapeshellarg($a), $extra);
$cmd = PHP_BINARY.' artisan serve '.implode(' ', $escaped);

passthru($cmd, $exitCode);
exit($exitCode);
