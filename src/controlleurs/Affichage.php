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
    public function afficherItem($id){
        echo "fonction afficher Item par ID<br>";
        $resultat="";
        $res = \mywishlist\models\Item::select('id', 'nom', 'descr')
            ->where('id', '=', $id)->get();
        foreach ($res as $item){
            $resultat=$resultat . 'ID: '.$item->id . '<br>Nom: '.$item->nom . '<br>Description: ' . $item->descr ."<br>";
        }
        if ($resultat==""){
            $resultat="Id incorrect";
        }
        echo $resultat;
    }


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