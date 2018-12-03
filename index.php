<?php
require_once 'vendor/autoload.php';
use Illuminate\Database\Capsule\Manager as DB;

$db = new DB();
$db->addConnection(parse_ini_file('./src/conf/conf.ini'));
$db->setAsGlobal();
$db->bootEloquent();
$app = new \Slim\Slim ;


$app->get('/affichage/afficherToutesLesListes',function (){
    $controlleurAffichage = new \mywishlist\controlleurs\Affichage();
    echo $controlleurAffichage->afficherToutLesItems();
});

$app->run();