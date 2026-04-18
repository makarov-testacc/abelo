<?php
define('DB_SETTINGS',[
   'host' => 'localhost',
   'port' => '3606',
   'user' => 'root',
   'pass' => '12345',
   'name' => 'abelo',
]);

define('POSTS_PER_CATEGORY',3);
define('SIMILAR_POSTS',3);

$stmtCache = []; //$dbh->prepare statements cache, just in case