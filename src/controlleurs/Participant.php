<?php
/**
 * Created by PhpStorm.
 */
namespace mywishlist\controlleurs;


use mywishlist\models\Item;
use mywishlist\models\Liste;
use mywishlist\vue\VueParticipant;

class Participant{


    /**
     * permet de reserver un item à partir de son id
     * @param $idItem
     */
    public function reserverItem($idItem,$nomUtilisateur,$message){
        $item = \mywishlist\models\Item::where('id','=',$idItem)->first();

        $utilisateur = \mywishlist\models\Utilisateur::where('uName'===$nomUtilisateur)->first();

        $reservation = new \mywishlist\models\Reservation();

        $reservation->idItem=$idItem;
        $reservation->idUser=$utilisateur->id;
        $reservation->message=$message;

        $reservation->save();




    }


}