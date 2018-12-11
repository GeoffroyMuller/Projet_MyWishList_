<?php
/**
 * Created by PhpStorm.
 * User: pc-iut
 * Date: 11/12/2018
 * Time: 11:43
 */

namespace mywishlist\models;


class Commentaire extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'commentaire';
    protected $user_id = 'user_id';
    protected $no = 'no';

    public $timestamps = false;

    public function commentaire(){
        return $this->belongsTo('\mywishlist\models\commentaire','user_id') ;
    }}