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
     * Methode permettant d'ajouter/modifier une image appartenant à un item
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
    public function supprimerImageItem($idItem){
        $item =\mywishlist\models\Liste::where('id', '=',$idItem)->first();
        $this->supprimerFichierImage($item->img);
        $item->img = null;
        $item->save();

        $vue = new VueParticipant($item, 'ITEM');
        $vue->render();
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
    public function modifierItem($nom,$descr,$image,$id){
        $item = \mywishlist\models\Item::where('id','=',$id)->first();
        $item->nom = $nom;
        $item->descr = $descr;
        $item->save();
        if(!is_null($image)){
            $this->modifierImageItem($image,$id);
        }


        $app = \Slim\Slim::getInstance();
        $app->redirect($app->urlFor("afficherItem",["id"=>$id]));

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