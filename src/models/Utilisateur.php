<?php
/**
 * Created by PhpStorm.
 * User: Lucas
 * Date: 26/12/2018
 * Time: 11:18
 */

namespace mywishlist\models;


class Utilisateur extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'utilisateur';
    protected $primaryKey = 'idUser';
    public $timestamps = false;

    public function listes(){
        return $this->hasMany('\mywishlist\models\Liste','user_id') ;
    }

}