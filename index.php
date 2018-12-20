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
    echo $controlleurAffichage->afficherLesListesDeSouhaits();
});

$app->get('/afficherListeItems/:id', function ($id){
    $controlleurAffichage = new \mywishlist\controlleurs\Affichage();
    echo $controlleurAffichage->afficherListeItems($id);
});
$app->get('/afficherItem/:id',function($id){
    $controlleurAffichage = new \mywishlist\controlleurs\Affichage();
    echo $controlleurAffichage->afficherItem($id);

});

$app->get('/ajouterImage/:id',function($id){
    if(!empty($_FILES['image'])){
        $controlleurCreateur = new \mywishlist\controlleurs\Createur();
        $controlleurCreateur->ajouterImageAItem($_FILES['image'],$id);
        $controlleurAffichage = new \mywishlist\controlleurs\Affichage();
        $controlleurAffichage->afficherItem($id);
    }else{
        //$controlleurAffichage->afficherErreur('Aucune image trouvée')
    }
});

$app->run();



