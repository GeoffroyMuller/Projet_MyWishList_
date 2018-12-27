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
    private function reserverItem($idItem){
        $item = \mywishlist\models\Item::where('id','=',$idItem)->first();

        $item->reserver();

    }
}