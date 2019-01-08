<?php
require_once 'vendor/autoload.php';
use Illuminate\Database\Capsule\Manager as DB;

$db = new DB();
$db->addConnection(parse_ini_file('./src/conf/conf.ini'));
$db->setAsGlobal();
$db->bootEloquent();

session_start();

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
})->name("listes");

$app->get('/afficherListeItems/:id', function ($id){
    $controlleurAffichage = new \mywishlist\controlleurs\Affichage();
    echo $controlleurAffichage->afficherListeItems($id);
})->name("afficherItemsListe");

/**
 * Url permettant l'affichage d'un item
 */
$app->get('/afficherItem/:id',function($id){
    $controlleurAffichage = new \mywishlist\controlleurs\Affichage();
    $controlleurAffichage->afficherItem($id);

})->name("afficherItem");


/*$app->post('/modifierImage/:id',function($id){
    if(!empty($_FILES['image'])){
        //On vérifie que le fichier est bien une image
        $extensions = array('.png','.jpeg','.gif','.jpg');
        $extension = strrchr($_FILES['image']['name'],'.');
        if(!in_array($extension,$extensions)){

        }else{
            $controlleurCreateur = new \mywishlist\controlleurs\Createur();
            // $controlleurCreateur->modifierImageItem($_FILES['image'],$id);
            $controlleurAffichage = new \mywishlist\controlleurs\Affichage();
            $controlleurAffichage->afficherItem($id);
        }
    }else{
        //$controlleurAffichage->afficherErreur('Aucune image trouvée')
    }
});*/

/**
 * Lien permettant d'afficher la page de modification de l'item
 */
$app->get('/modifierItem/:id',function($id){
    if(isset($_SESSION['profile']) && \mywishlist\controlleurs\ControleurInternaute::testerAppartenanceItem($id)) {
        $controlleurAffichage = new \mywishlist\controlleurs\Affichage();
        $controlleurAffichage->afficherItemModification($id);
    }else{
        $app = \Slim\Slim::getInstance();
        $app->redirect($app->urlFor('erreur',['msg'=>'Vous devez être le créateur de l\'item pour pouvoir le modifier']));
    }

})->name("modifierItem");

/**
 * Url permettant d'ajouter un commentaire
 */
$app->post('/ajoutCommentaire:id',function($id){
    $controlleurCreateur = new \mywishlist\models\Commentaire();
    //Vérification des données entrée par l'utilisateur
    if(isset($_POST['message'])){
        $message =filter_var($_POST['message'],FILTER_SANITIZE_STRING);
    }else{
        $message="";
    }
})->name("ajoutCommentaire");

/**
 * Url permettant d'appliquer les modifications d'un item
 */
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
            $image=null;
        }else{
            $image=$_FILES['itemImageModification'];
        }
    }else{
        $image=null;
    }

    $controlleurCreateur->modifierItem($nom,$descr,$image,$id,$_POST['tarifItem']);

})->name("application-modification");

/**
 * Url permettant d'afficher la page de modification des images d'un item
 */
$app->get('/modifierLesimages/:id', function($id){
    if(isset($_SESSION['profile']) && \mywishlist\controlleurs\ControleurInternaute::testerAppartenanceItem($id)){
        $controlleurAffichage = new \mywishlist\controlleurs\Affichage();
        $controlleurAffichage->afficherImageModification($id);
    }else{
        $app = \Slim\Slim::getInstance();
        $app->redirect($app->urlFor('erreur',['msg'=>'Vous devez être le créateur de l\'item pour pouvoir le modifier']));
    }

})->name('modifierImageItem');

/**
 * Url permettant l'application des modifications relative aux images d'un item
 */
$app->post('/applicationModificationImages/:id', function($id){

    $controlleurCreateur = new mywishlist\controlleurs\Createur();
    if(isset($_POST['del'])){
        $controlleurCreateur->supprimerImageItem($_POST['del']);
    }

    if(isset($_POST['add'])){
        foreach ($_POST['add'] as $img){
            $controlleurCreateur->ajouterImageItem($id,$img);
        }
    }

    if(isset($_FILES['nouvellesImagesItem']) && !empty($_FILES['nouvellesImagesItem']['name'])){


        //On vérifie que le fichier est bien une image
        $extensions = array('.png','.jpeg','.gif','.jpg');
        $extension = strrchr($_FILES['nouvellesImagesItem']['name'],'.');
        if(!in_array($extension,$extensions)){
            $image=null;
        }else{
            $image=$_FILES['nouvellesImagesItem'];
        }
        $nomImagesUpload = $controlleurCreateur->uploadImage($image);
        //On ajoute les images upload à l'item
        foreach ($nomImagesUpload as $nom){
            $controlleurCreateur->ajouterImageItem($id,null,$nom);
        }
    }

    $redirect = \Slim\Slim::getInstance();
    $redirect->redirect($redirect->urlFor("modifierImageItem",["id"=>$id]));
})->name('appModifIMage');

/**
 * Url permettant d'obtenir la page d'inscription
 */
$app->get('/inscription/',function (){
    if(!isset($_SESSION['profile'])){
        $controleurAffichage = new mywishlist\controlleurs\Affichage();
        $controleurAffichage->afficherInscription();
    }else{
        $app = \Slim\Slim::getInstance();
        $app->redirect('listes');
    }

})->name('inscription');

/**
 * Url permettant d'obtenir la page de connexion
 */
$app->get('/connexion/',function (){
        $controleurAffichage = new mywishlist\controlleurs\Affichage();
        $controleurAffichage->afficherConnexion();
})->name('connexion');


/**
 * Url permettant de lancer le processus d'inscription d'un internaute
 */
$app->post('/inscriptionprocess/',function (){
    if(isset($_POST['username']) && isset($_POST['password'])){
        $username = filter_var($_POST['username'],FILTER_SANITIZE_STRING);
        $controleur = new mywishlist\controlleurs\ControleurInternaute();
        $controleur->inscrire($username, $_POST['password']);
    }else{
        $app = \Slim\Slim::getInstance();
        $app->redirect($app->urlFor('erreur',['msg'=>'Veuillez entrer un nom d\'utilisateur et un mot de passe']));
    }
    $app = \Slim\Slim::getInstance();
    $app->redirect($app->urlFor('connexion'));

})->name('inscriptionprocess');

/**
 * Url permettant de lancer le processus de connexion d'un internaute
 */
$app->post('/connexionprocess/',function (){
    if(isset($_POST['username']) && isset($_POST['password'])){
        $username = filter_var($_POST['username'],FILTER_SANITIZE_STRING);

        $controleur = new mywishlist\controlleurs\ControleurInternaute();
        $controleur->seConnecter($username, $_POST['password']);
    }else{
        $app = \Slim\Slim::getInstance();
        $app->redirect($app->urlFor('erreur',['msg'=>'Veuillez entrer un nom d\'utilisateur et un mot de passe']));
    }
    $app = \Slim\Slim::getInstance();
    $app->redirect($app->urlFor('listes'));

})->name('connexionprocess');

/**
 * Url permettant d'afficher la page du profil de l'utilisateur
 */
$app->get('/profil/', function(){
    $controleur = new mywishlist\controlleurs\Affichage();

    if(isset($_SESSION['profile'])){
        $controleur->afficherProfil();
    }else{
        $app = \Slim\Slim::getInstance();
        $app->redirect($app->urlFor('inscription'));
    }
})->name('profil');

/**
 * Url permettant la déconnexion
 */
$app->get('/deconnexion/', function (){
    echo "SALUT";
    $controleur = new \mywishlist\controlleurs\ControleurInternaute();
    $controleur->deconnexion();
    $app = \Slim\Slim::getInstance();
    $app->redirect($app->urlFor('listes'));
})->name('deconnexion');

/**
 * Url permettant la suppression de compte
 */
$app->get('/suppCompte/', function (){
    $controleur = new \mywishlist\controlleurs\ControleurInternaute();
    $controleur->suppCompte();
    $app = \Slim\Slim::getInstance();
    $app->redirect($app->urlFor('listes'));
})->name('suppCompte');

/**
 * Url permettant d'acceder a la page de modification du profil
 */
$app->get('/profilModif/', function (){
    if(isset($_SESSION['profile'])) {
        $controleur = new mywishlist\controlleurs\Affichage();
        $controleur->afficherProfilModification();
    }else{
        $app = \Slim\Slim::getInstance();
        $app->redirect($app->urlFor('listes'));
    }
})->name('profilModif');

/**
 * Url permettant d'acceder a la page de creation d'un item
 */
$app->post('/creerUnItem/:id',function($id){
    $controleur = new \mywishlist\controlleurs\Affichage();
    $controleur->afficherCreationItem($id);
})->name('creationItemPage');

/**
 * Url permettant de creer un item
 */
$app->post('/createur/creerUnItem/:id',function($id){
    $controleur = new \mywishlist\controlleurs\Createur();
$nomp = ""; $descrp = ""; $imgp = ""; $urlp = ""; $tarifp = "";
    if(isset($_POST['nomItem'])) {
        $nomp = filter_var($_POST['nomItem'], FILTER_SANITIZE_STRING);
    }
    if(isset($_POST['descrItem'])) {
        $descrp = filter_var($_POST['descrItem'], FILTER_SANITIZE_SPECIAL_CHARS);
    }
    if(isset($_POST['expListe'])) {
        $tarif = filter_var($_POST['descrItem'], FILTER_SANITIZE_NUMBER_INT);
    }
    $controleur->creerUnItem($id, $nomp, $descrp, $imgp, $urlp, $tarifp);
})->name('creationItem');
/**
 * Url permettant d'acceder a la page de creation d'une liste
 */
$app->get('/creerUneListe/',function(){
    $controleur = new \mywishlist\controlleurs\Affichage();
    $controleur->afficherCreationListe();
})->name('creationListePage');

/**
* Url permettant de creer une liste
*/
$app->post('/createur/creerUneListe/', function(){
    $controleur = new \mywishlist\controlleurs\Createur();
    $titre = "";$descript = "";$expir = "";$token = "";
    if(isset($_POST['nomListe'])) {
        $titre = filter_var($_POST['nomListe'], FILTER_SANITIZE_STRING);
    }
    if(isset($_POST['descrListe'])) {
        $descript = filter_var($_POST['descrListe'], FILTER_SANITIZE_SPECIAL_CHARS);
    }
    if(isset($_POST['expListe'])) {
        $expir = filter_var($_POST['expListe'], FILTER_SANITIZE_NUMBER_INT);
    }
    if(isset($_POST['publiqueListe'])) {
        $token = filter_var($_POST['publiqueListe'], FILTER_SANITIZE_STRING);
    }
    try {
        if(isset($_SESSION['profile'])){
            $controleur->creerUneListe($_SESSION['profile']['userId'], $titre, $descript, $expir, $token);
        }else{
            $controleur->creerUneListeNonConnecte($titre, $descript, $expir, $token);
        }

    } catch (Exception $e){
        //la liste ne peut pas etre ajouter
    }
    $app = \Slim\Slim::getInstance();
    $app->redirect($app->urlFor('listes'));
})->name('creationListe');
/**
 * URL permettant d'acceder a la page "Mes Listes"
 */
$app->get('/mesListes/',function (){
    if(isset($_SESSION['profile'])){
       $controleur = new mywishlist\controlleurs\Affichage();
       $controleur->afficherMesListes();
    }else{
        /*
         * Utilisateur non log peut accéder à ses listes stocké dans des cookies
         */
        $app = \Slim\Slim::getInstance();
        $app->redirect($app->urlFor('creationListePage'));
    }
})->name("mesListes");

/**
 * URL permettant d'afficher une liste avec son token
 */
$app->get('/afficherListeToken/',function($token){
    $controleur = new mywishlist\controlleurs\Affichage();
    $idListe = $controleur->afficherListeToken($token);

    $app = \Slim\Slim::getInstance();

    if($idListe == -1){
        $app->redirect($app->urlFor('erreur',['msg'=>'Le token entré n\'existe pas']));
    }else{
        $app->redirect($app->urlFor('erreur',['id'=>$idListe]));

    }


})->name("afficherListeAvecToken");

$app->post('/erreur/:msg', function($msg){
    $controleur = new mywishlist\controlleurs\Affichage();
    $controleur->afficherErreur($msg);
})->name("erreur");

$app->run();



