<?php
/**
 * Created by PhpStorm.
 * User: Lucas
 * Date: 03/12/2018
 * Time: 15:14
 */

namespace mywishlist\controlleurs;


use mywishlist\models\Item;
use mywishlist\models\Liste;
use mywishlist\vue\VueParticipant;

class Affichage
{

    public function itemsListe($noliste){
        echo "====Test: Lister les items d'une liste===="."<br>";
        $res = \mywishlist\models\Liste::where("no","=",$noliste);
        echo $res->titre;
        echo "=============================="."<br>";
    }

    /**
     * Méthode affichant un item
     * @param $id
     *      Id de l'item
     * @return string
     *      Code html de la vue
     */
    public function afficherItem($id){
        $res['item'] = \mywishlist\models\Item::where('id', '=', $id)->first();
        $images = $res['item']->images()->get();
        if(count($images) == 0){
            $res['images'][]=null;
        }else{
            foreach($images as $image) {
                $res['images'][] = $image;
            }
        }

        $vue = new VueParticipant($res,'ITEM');
        return $vue->render();
    }
    /**
     * affiche les items d'une liste choisie par son id 
     * @param $idlisteSouhait : id de la liste choisie
     */
    public function afficherListeItems($idlisteSouhait){
        $resultat = array();
        $liste_de_souhait = \mywishlist\models\Liste::where('no', '=', $idlisteSouhait)->first();
        $resultat['liste'] = $liste_de_souhait;
        $ListeItems = $liste_de_souhait->items()->get();
        foreach ($ListeItems as $item){
            $resultat['items'][]=$item;
        }
        $vue = new VueParticipant($resultat,"LIST_ITEMS");
        return $vue->render();
    }

    /**
     * Méthode permettant l'affichage de toutes les listes de souhait
     */
    public function afficherLesListesDeSouhaits(){
        $resultat = \mywishlist\models\Liste::select('no','user_id','titre','description','expiration')->get();
        $vue = new VueParticipant($resultat,"LIST_VIEW");
        return $vue->render();
    }

    /**
     * Méthode permettant l'affichage de la page de modification de l'item souhaité
     * @param $id
     */
    public function afficherItemModification($id){
        $res['item'] = \mywishlist\models\Item::where('id', '=', $id)->first();
        $images = $res['item']->images()->get();
        if(count($images) == 0){
            $res['images'][]=null;
        }else{
            foreach($images as $image) {
                $res['images'][] = $image;
            }
        }



        $vue = new VueParticipant($res,'ITEM_MODIFICATION');
        $vue->render();
    }

    /**
     * Méthode permettant d'afficher la page de modification des images d'un item
     * @param $id
     *      Id de l'item
     */
    public function afficherImageModification($id){
        $item = \mywishlist\models\Item::where('id','=',$id)->first();
        $res['item']=$item;

        //On récupére les images de l'item
        $images = $item->images()->get();

        if(count($images)==0){
            $res['imagesUtilise']=null;
        }else{
            foreach ($images as $image){
                $res['imagesUtilise'][] = $image;
                $comp[] = $image->nom;
            }
        }

        $images = \mywishlist\models\Image::all();



        foreach ($images as $image){
            if(isset($comp)){
                if(!in_array($image->nom,$comp)){
                    $res['imageProposees'][] = $image;
                }
            }else{
                $res['imageProposees'][] = $image;
            }

        }

        $vue = new VueParticipant($res,'IMAGE_MODIFICATION');
        $vue->render();
    }

    /**
     * Méthode permettant d'afficher la page d'inscirption
     */
    public function afficherInscription(){
        $vue = new \mywishlist\vue\VueParticipant(null,'INSCRIPTION');
        $vue->render();
    }

    /**
     * Méthode permettant d'afficher la page de connexion
     */
    public function afficherConnexion(){
        $vue = new \mywishlist\vue\VueParticipant(null,'CONNEXION');
        $vue->render();
    }

    /**
     * Méthode permettant d'afficher la page du profil
     */
    public function afficherProfil(){
        $res['uName'] = $_SESSION['profile']['username'];
        $res['listes'] = \mywishlist\models\Utilisateur::where('idUser','=',$_SESSION['profile']['userId'])->first()->listes();

        $vue = new \mywishlist\vue\VueParticipant($res,'PROFIL');
        $vue->render();

    }

    /**
     * Méthode permettant d'afficher la page de modification du profil
     */
    public function afficherProfilModification(){
        $vue = new \mywishlist\vue\VueParticipant(null,'PROFIL_MODIFICATION');
        $vue->render();

    }


}