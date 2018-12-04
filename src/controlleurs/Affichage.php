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
        echo "fonction afficher Item par ID";
        $res = item::select( 'id', 'nom', 'descr')
            ->where( 'id','=',$id) ;
        echo $res->id + ", " + $res->nom + "," + $res->descr ;
    }
}