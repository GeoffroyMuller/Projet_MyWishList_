<?php
require_once 'vendor/autoload.php';
use Illuminate\Database\Capsule\Manager as DB;

$db = new DB();
$db->addConnection(parse_ini_file('./src/conf/conf.ini'));
$db->setAsGlobal();
$db->bootEloquent();
$app = new \Slim\Slim ;

/*
 * Un seul ECHO partout, le ECHO doit etre dans la methode render de la VUE
 * href = demander l'url à slim
 */
$app->get('/listes/',function (){
    $controlleurAffichage = new \mywishlist\controlleurs\Affichage();
    echo $controlleurAffichage->afficherToutLesItems();
});

$app->get('/affichage/afficherListeItems/:idliste', function (){
    $controlleurAffichage = new \mywishlist\controlleurs\Affichage();
    $controlleurAffichage->afficherListeItems($idliste);
});
$app->get('/affichage/afficherItem/:id',function($id){
    $controlleurAffichage = new \mywishlist\controlleurs\Affichage();
    echo $controlleurAffichage->afficherItem($id);

});

$app->run();



