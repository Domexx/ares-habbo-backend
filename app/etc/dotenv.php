<?php

$dotEnv = Dotenv\Dotenv::createImmutable(__DIR__, '../../.env');
if (file_exists(__DIR__ . '/../../' . '.env')) {
    $dotEnv->load();
}