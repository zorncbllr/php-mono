<?php

// Edit database configuration.

return [
    "host" => getenv('DATABASE_HOST'),
    "port" => getenv('DATABASE_PORT'),
    "user" => getenv('DATABASE_USER'),
    "password" => getenv('DATABASE_PASSWORD'),
    "dbname" => getenv('DATABASE_NAME'),
    "charset" => getenv('DATABASE_CHARSET')
];
