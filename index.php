<?php
require_once 'vendor/autoload.php';

$app = new \Slim\Slim ;


$app->get('/affichage/afficherItemsListe', function($noliste){
    echo '===jhonny====' ;
    $control = new \mywishlist\controlleurs\Affichage();
    $control->itemSListe($noliste);
});

/**
 * Created by PhpStorm.
 * User: theob
 * Date: 03/12/2018
 * Time: 15:04
 */

$app->get('/affichage/afficherTouteLesListes',function (){

});

