#!/usr/bin/php
<?php
declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

$fetchers = [
    'file' => \SlackBatch\Fetchers\FileParser::class,
];
$sender = new \SlackBatch\InviteSender();

$app = new \SlackBatch\App($fetchers, $sender);
$app->run();
