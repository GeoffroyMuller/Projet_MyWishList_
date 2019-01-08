<?php
/**
 * Created by PhpStorm.
 * User: Lucas
 * Date: 10/12/2018
 * Time: 15:34
 */

namespace mywishlist\controlleurs;


use mywishlist\models\Item;
use mywishlist\models\Liste;
use mywishlist\vue\VueParticipant;

class Createur
{
    /**
     * Methode permettant d'ajouter un message à une liste
     * @param $user_id
     *      id de l'utilisateur
     * @param $no
     *      Id de la liste
     * @param $message
     *      message a ajouter
     * @return String
     */
    /*public function ajouterMessage($user_id, $no, $message){
        $res = \mywishlist\models\Commentaire::INSERT INTO Commentaire VALUES ($user_id, $no, $message)->get();
        $vue = new VueParticipant($res,"Commentaire");
        return $vue->render();
        }*/

    /**
     * Methode permettant d'ajouter/modifier l'image principale appartenant à un item
     * @param $file
     *      Fichier Image
     * @param $idItem
     *      Id de l'item où il faut ajouter une image
     */
    private function modifierImageItem($file,$idItem){
        //On récupére le nom du fichier
        $nomDufichierDossierPermanent = ($this->recupererNbImageDossier()+1).strrchr($file['name'],'.');
        //On récupére l'item et on update le nom de l'image
        $item = \mywishlist\models\Item::where('id','=',$idItem)->first();
        $item->id = $idItem;
        $path = $_SERVER["DOCUMENT_ROOT"];


            //On déplace le fichier dans le répertoire définitif avec un nom différent pour éviter les caractére spéciaux
          if (!(move_uploaded_file($file['tmp_name'], "$path/img/".$nomDufichierDossierPermanent ))) {
                /*
                 * Throw une erreur
                 */
            }
        if(!is_null($item->img)){
            //L'item à déja une image et on souhaite juste la mettre à jour
            $this->supprimerFichierImage($item->img);
        }
        $item->img = $nomDufichierDossierPermanent;
        $item->save();
    }

    /**
     * Méthode permettant de renommer les images envoyé par l'utilisateur et les déplaces dans un dossier permanent
     * @param $files
     * @return $noms
     */
    public function uploadImage($file){
        $path = $_SERVER["DOCUMENT_ROOT"];
        $noms[]=$generationNom= ($this->recupererNbImageDossier()+1).strrchr($file['name'],'.');
        move_uploaded_file($file['tmp_name'], "$path/img/".$generationNom);
        return $noms;
    }

    /**
     * Méthode permettant de compter le nombre d'image dans le dossier /img. Cette méthode est utilisé pour donner un nom aux image envoyé par l'utilisateur
     * @return int
     *      Le nombre de fichier trouver dans le répertoire /img
     */
    private function recupererNbImageDossier(){
        $path = $_SERVER["DOCUMENT_ROOT"];
        return count(glob("$path/img/*.*"));
    }

    /**
     * Methode permettant de supprimer un fichier image du dossier permanent /img/
     * @param $nomImage
     *      Nom de l'image à supprimer
     */
    private function supprimerFichierImage($nomImage){
        if(file_exists('/img/'.$nomImage)){
            unlink('/img/'.$nomImage);
        }
    }

    /**
     * Methode permettant de supprimer une image lier à un item
     * @param $idItem
     *      Id de l'item
     */
    public function supprimerImageItem($images){
        foreach($images as $value){
            $imageBD = \mywishlist\models\Image::where('id','=',$value)->first();
            $imageBD->idItem=null;
            $imageBD->save();
        }
    }

    /**
     * Méthode permettant l'ajout d'images selectionnées à un item
     * @param $images
     *      Les images choisi par l'utilisateur par l'intermédiaire d'une checkbox
     * @param $id
     */
    public function ajouterImageItem($id,$image,$nom=null){
            $imageBD = new \mywishlist\models\Image();
            if(is_null($nom)){
                $imageBD->nom = \mywishlist\models\Image::where('id','=',$image)->first()->nom;
            }else{
                $imageBD->nom = $nom;
            }
            $imageBD->idItem=$id;
            $imageBD->save();

}

    /**
     * Méthode permettant de modifier un item, tout les paramétres on au préalable été vérifier et nettoyé
     * @param $nom
     *      Nouveau nom de l'item
     * @param $descr
     *      Nouvelle description de l'item
     * @param $image
     *      Nouvelle image de l'item
     */
    public function modifierItem($nom,$descr,$image,$id,$tarif){
        $item = \mywishlist\models\Item::where('id','=',$id)->first();
        $item->nom = $nom;
        $item->descr = $descr;
        $item->tarif=$tarif;
        $item->save();
        if(!is_null($image)){
            $this->modifierImageItem($image,$id);
        }


        $app = \Slim\Slim::getInstance();
        $app->redirect($app->urlFor("afficherItem",["id"=>$id]));

    }


    public function creerUneListe($user_idp,$titrep,$descrip,$expir,$tokenp){
        $liste = new \mywishlist\models\Liste();
        //$liste->no = $nop;
        $liste->user_id = $user_idp;
        $liste->titre = $titrep;
        $liste->description = $descrip;
        $liste->expiration = $expir;
        $liste->token = $tokenp;
        $liste->save();
    }

    /**
     * Méthode permettant la création d'une liste par un utilisateur non connecté
     * @param $titre
     * @param $descript
     * @param $expir
     * @param $token
     */
    public function creerUneListeNonConnecte($titre, $descript, $expir)
    {
        $liste = new \mywishlist\models\Liste();

        $liste->user_id = -1;
        $liste->titre = $titre;
        $liste->description = $descript;
        $liste->expiration = $expir;
        $token = uniqid();
        $liste->token = $token;
        $liste->save();

        setcookie($titre, "$token",
            time() + 60*60*24*30, "/cookie/liste/" );


}

    /*public function creerListe($tablist){
        //$no, $user_id, $titre, $description, $expiration, $token
        $resliste = new \mywishlist\models\Liste();
        $resliste->no = $no;
        $resliste->user_id = $user_id;
        $resliste->titre = $titre;
        $resliste->description = $description;
        $resliste->expiration = $expiration;
        $resliste->token = $token;
    }*/
}