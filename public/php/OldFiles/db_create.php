<?php
$statements = [
    'CREATE TABLE IF NOT EXISTS app_info( 
        record_id           INT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        access_token        VARCHAR(255) NOT NULL,
        task_id             VARCHAR(255) NOT NULL,
        created_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
        last_update         TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )',
    'CREATE TABLE IF NOT EXISTS app_users( 
        user_id           INT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_type         INT(20) NOT NULL,
        email             VARCHAR(255) NOT NULL,
        username          VARCHAR(255) NOT NULL,
        password          VARCHAR(255) NOT NULL,
        token             VARCHAR(255) NOT NULL,
        created_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
        last_update       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )',
];

// connect to the database
$pdo = require 'connect.php';

// execute SQL statements
foreach ($statements as $statement) {
    $pdo->exec($statement);
}
