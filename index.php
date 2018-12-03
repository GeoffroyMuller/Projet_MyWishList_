<?php
require_once 'vendor/autoload.php';

$app = new \Slim\Slim ;

echo 'Test';

$app->get('/affichage/afficherTouteLesListes',function (){
    //$controlleurAffichage = new \mywishlist\controlleurs\Affichage();
    echo 'SALUT';
    //echo $controlleurAffichage->afficherToutLesItems();
});

$app->run();