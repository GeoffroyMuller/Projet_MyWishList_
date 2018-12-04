<?php
/**
 * Created by PhpStorm.
 * User: Lucas
 * Date: 03/12/2018
 * Time: 15:14
 */

namespace mywishlist\controlleurs;


class Affichage
{

    public function afficherToutLesItems(){
        $resultat="";
        $listes_de_souhaits = \mywishlist\models\Liste::select('user_id','titre','description','expiration')->get();
        foreach ($listes_de_souhaits as $liste){
            $resultat=$resultat.$liste->no . ':'.$liste->user_id . ':' . $liste->titre . ':' . $liste->description . ':' . $liste->expiration."<br>";
        }
        return $resultat;
    }
    public function afficherListeItems($idlisteSouhait){
        $resultat="";
        echo "Items dans la liste d'id: ".$idlisteSouhait."<br>";
        $liste_de_souhait = \mywishlist\models\Liste::where('no', '=', $idlisteSouhait)->first();
        $ListeItems = $liste_de_souhait->items()->get();
        foreach ($ListeItems as $Item){
            $resultat=$resultat.$Item->id.":".$Item->liste_id.":".$Item->nom.":".$Item->descr.":".$Item->img.":".$Item->url.":".$Item->tarif."<br>";
        }
        return $resultat;
    }
}