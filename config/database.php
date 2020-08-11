<?php
use Illuminate\Database\Capsule\Manager as Capsule;

return function($connection) {
    $capsule = new Capsule;
    $capsule->addConnection($connection);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();
}
?>
