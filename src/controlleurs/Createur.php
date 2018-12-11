<?php
/**
 * Created by PhpStorm.
 * User: Lucas
 * Date: 10/12/2018
 * Time: 15:34
 */

namespace mywishlist\controlleurs;


class Createur
{
    public function ajouterMessage($user_id, $no, $message){
        $res = \mywishlist\models\Commentaire::INSERT INTO Commentaire VALUES ($user_id, $no, $message)->get();
        $vue = new VueParticipant($res,"Commentaire");
        return $vue->render();
    }
}