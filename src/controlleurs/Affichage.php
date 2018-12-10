<?php
/**
 * Created by PhpStorm.
 * User: Lucas
 * Date: 03/12/2018
 * Time: 15:14
 */

namespace mywishlist\controlleurs;


use mywishlist\vue\VueParticipant;

class Affichage
{
    /**
     * Méthode affichant un item
     * @param $id
     *      Id de l'item
     * @return string
     *      Code html de la vue
     */
    public function afficherItem($id){

        $res = \mywishlist\models\Item::select('id', 'nom', 'descr')
            ->where('id', '=', $id)->get();
        $vue = new VueParticipant($res,"ITEM");
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

}