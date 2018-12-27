<?php
/**
 * Created by PhpStorm.
 * User: Lucas
 * Date: 19/11/2018
 * Time: 15:35
 */

namespace mywishlist\models;


class Item extends \Illuminate\Database\Eloquent\Model
{

    protected $table = 'item';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $reserve;


    public function liste(){
        return $this->belongsTo('\mywishlist\models\Liste','liste_id') ;
    }


    public function reserver($particpant){
        if($this->reserve==null){
            $this->reserve==$particpant;
        }
        else{
            /**
             * message d'erreur ur la page item deja réserver
             * ou empecher l'action si item deja reqserver?
             */
        }
    }

    public function suprrReservation(){
        $this->reserve=null;
    }

    public function images(){
        return $this->hasMany('\mywishlist\models\Image','idItem') ;

    }


}