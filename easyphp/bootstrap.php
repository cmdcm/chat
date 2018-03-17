<?php
use Illuminate\Database\Capsule\Manager as Capsule;

// Eloquent ORM
$config  = require EASY_CONFIG_PATH . '/database.php';

$capsule = new Capsule;

$capsule->addConnection($config);

$capsule->bootEloquent();