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
            $resultat=$resultat . $item->id . ', '.$item->nom . ', ' . $item->descr ."<br>";
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
}