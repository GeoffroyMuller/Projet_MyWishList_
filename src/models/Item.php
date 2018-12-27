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


    public function liste(){
        return $this->belongsTo('\mywishlist\models\Liste','liste_id') ;
    }

    public function reserver(){
        if($this->reserve){

        }
        else{
            $this
        }
    }


}