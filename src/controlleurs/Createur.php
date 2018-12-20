<?php
/**
 * Created by PhpStorm.
 * User: Lucas
 * Date: 10/12/2018
 * Time: 15:34
 */

namespace mywishlist\controlleurs;


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
     */
    public function ajouterMessage($user_id, $no, $message){
        $res = \mywishlist\models\Commentaire::INSERT INTO Commentaire VALUES ($user_id, $no, $message)->get();
        $vue = new VueParticipant($res,"Commentaire");
        return $vue->render();
        }

    /**
     * Methode permettant d'ajouter une image à un item
     * @param $file
     *      Fichier Image
     * @param $idItem
     *      Id de l'item où il faut ajouter une image
     */
    public function ajouterImageAItem($file,$idItem){
        //On récupére le nom du fichier
        $fileName = $file['name'];
        //On récupére l'item et on update le nom de l'image
        $item = \mywishlist\models\Liste::where('id', '=',$idItem);
        if($item->img == null){
            $item->img = $fileName;
            $item->save();
            if(file_exists('/img/'.$fileName)){
                move_uploaded_file($fileName,'/img');
            }else{
                /*
                 * To do exception fichier existe déja
                 */
            }
        }else{
            /*
             * TO do Exception car l'item à déja une image
             */
        }

    }

    public function creerListe($tablist){
        //$no, $user_id, $titre, $description, $expiration, $token
        $resliste = new \mywishlist\models\Liste();
        $resliste->no = $no;
        $resliste->user_id = $user_id;
        $resliste->titre = $titre;
        $resliste->description = $description;
        $resliste->expiration = $expiration;
        $resliste->token = $token;
    }
}