<?php
/**
 * Created by PhpStorm.
 * User: Lucas
 * Date: 03/12/2018
 * Time: 15:14
 */

namespace mywishlist\controlleurs;


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
        $res = \mywishlist\models\Item::where('id', '=', $id)->first();
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
        $ListeItems = $liste_de_souhait->items()->get();
        foreach ($ListeItems as $item){
            array_push($resultat, $item);
        }
        $vue = new VueParticipant($resultat,"LIST_ITEMS");
        return $vue->render();
    }

    /**
     * Méthode permettant l'affichage de toutes les listes de souhait
     */
    public function afficherLesListesDeSouhaits(){
        $resultat = \mywishlist\models\Liste::select('user_id','titre','description','expiration')->get();
        $vue = new VueParticipant($resultat,"LIST_VIEW");
        return $vue->render();
    }


}