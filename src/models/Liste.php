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
        return $this->hasMany('\models\item','liste_no');
    }



}