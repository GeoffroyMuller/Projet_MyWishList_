<?php
/**
 * Created by PhpStorm.
 * User: Lucas
 * Date: 19/11/2018
 * Time: 15:49
 */

namespace mywishlist\models;


class Liste extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'liste';
    protected $primaryKey = 'no';
    public $timestamps = false;

    public function items(){
        return $this->hasMany('mywishlist\models\Item','liste_id');
    }

    public static function cmp($a,$b){
        if(strtotime($a->expiration) > strtotime($b->expiration)){ //  valeur précedente $a comparée à la valeur suivante $b
            return 1;
        }elseif (strtotime($a->expiration) < strtotime($b->expiration)){
            return -1;
        }
        return 0;
    }





}