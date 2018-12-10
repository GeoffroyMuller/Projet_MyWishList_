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


    public function afficherListeItems($idlisteSouhait){
        $resultat = array();
        $liste_de_souhait = \mywishlist\models\Liste::where('no', '=', $idlisteSouhait)->first();
        $ListeItems = $liste_de_souhait->items()->get();
        foreach ($ListeItems as $item){
            array_push($resultat, $this->afficherItem($item->no));
        }
        $vue = new VueParticipant($resultat,"ITEM");
        return $vue->render();
    }
}