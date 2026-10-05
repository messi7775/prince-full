<?php
declare(strict_types=1);

/*
 * External hosting configuration
 * Domain: prince-n.kesug.com
 * MySQL: InfinityFree
 *
 * IMPORTANT:
 * The password supplied in the request was masked as XXXXXXXXXXX.
 * Replace DB_PASS with the real MySQL password before uploading.
 */

const DB_HOST = 'sql302.infinityfree.com';
const DB_NAME = 'if0_43097781_prince';
const DB_USER = 'if0_43097781';
const DB_PASS = '7CF3Sf3xDxbU5L';
const DB_PORT = 3306;

$dsn = 'mysql:host=' . DB_HOST .
       ';port=' . DB_PORT .
       ';dbname=' . DB_NAME .
       ';charset=utf8mb4';

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::ATTR_TIMEOUT            => 8,
];

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    http_response_code(500);
    exit('تعذر الاتصال بقاعدة البيانات الخارجية.');
}
