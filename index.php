<?php
require_once 'vendor/autoload.php';

$app = new \Slim\Slim ;

$app->get('/affichage/afficherTouteLesListes',function (){

});

$app->get('/affichage/afficherListeSouhait', function (){
    echo 'afficherListeSouhait';
});

$app->run();

