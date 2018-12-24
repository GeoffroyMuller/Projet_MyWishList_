<?php
/**
 * Created by PhpStorm.
 * User: Lucas
 * Date: 24/12/2018
 * Time: 11:38
 */

namespace mywishlist\models;


class Image extends \Illuminate\Database\Eloquent\Model
{

    protected $table = 'image';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function item(){
        return $this->belongsTo('\mywishlist\models\Item','id') ;
    }
}