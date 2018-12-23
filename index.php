<?php
require_once 'vendor/autoload.php';
use Illuminate\Database\Capsule\Manager as DB;

$db = new DB();
$db->addConnection(parse_ini_file('./src/conf/conf.ini'));
$db->setAsGlobal();
$db->bootEloquent();
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

/*
 * Un seul ECHO partout, le ECHO doit etre dans la methode render de la VUE
 * href = demander l'url à slim
 */
$app->get('/listes/',function (){
    $controlleurAffichage = new \mywishlist\controlleurs\Affichage();
    echo $controlleurAffichage->afficherLesListesDeSouhaits();
})->name("listes");;

$app->get('/afficherListeItems/:id', function ($id){
    $controlleurAffichage = new \mywishlist\controlleurs\Affichage();
    echo $controlleurAffichage->afficherListeItems($id);
})->name("afficherItemsListe");


$app->get('/afficherItem/:id',function($id){
    $controlleurAffichage = new \mywishlist\controlleurs\Affichage();
    $controlleurAffichage->afficherItem($id);

})->name("afficherItem");

$app->post('/modifierImage/:id',function($id){
    if(!empty($_FILES['image'])){
        //On vérifie que le fichier est bien une image
        $extensions = array('.png','.jpeg','.gif','.jpg');
        $extension = strrchr($_FILES['image']['name'],'.');
        if(!in_array($extension,$extensions)){
            /*
             * Throw exception ceci n'est pas une image
             */
        }else{
            $controlleurCreateur = new \mywishlist\controlleurs\Createur();
            $controlleurCreateur->modifierImageItem($_FILES['image'],$id);
            $controlleurAffichage = new \mywishlist\controlleurs\Affichage();
            $controlleurAffichage->afficherItem($id);
        }
    }else{
        //$controlleurAffichage->afficherErreur('Aucune image trouvée')
    }
});

/**
 * Lien permettant d'afficher la page de modification de l'item
 */
$app->get('/modifierItem/:id',function($id){
    /**
     * To do vérifier les droits
     */
    $controlleurAffichage = new \mywishlist\controlleurs\Affichage();
    $controlleurAffichage->afficherItemModification($id);

})->name("modifierItem");

$app->post('/applicationDesModificationsItem/:id', function($id){
   $controlleurCreateur = new mywishlist\controlleurs\Createur();
   //Vérification des données entrée par l'utilisateur
    if(isset($_POST['titre-item-modification'])){
        $nom =filter_var($_POST['titre-item-modification'],FILTER_SANITIZE_STRING);
    }else{
        $nom="";
    }

    if(isset($_POST['description-item-modification'])){
        $descr = filter_var($_POST['description-item-modification'], FILTER_SANITIZE_STRING);
    }else{
        $descr="";
    }
    //On vérifie si l'image à été modifié et si le fichier est bien une image
    if(!empty($_FILES['itemImageModification'])){
        //On vérifie que le fichier est bien une image
        $extensions = array('.png','.jpeg','.gif','.jpg');
        $extension = strrchr($_FILES['itemImageModification']['name'],'.');
        if(!in_array($extension,$extensions)){
            /*
             * Throw exception ceci n'est pas une image
             */
            $image=null;
        }else{
            $image=$_FILES['itemImageModification'];
        }
    }else{
        $image=null;
    }

   $controlleurCreateur->modifierItem($nom,$descr,$image,$id);

})->name("application-modification");



$app->run();



