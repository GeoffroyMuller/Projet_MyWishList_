<?php
/**
 * Created by PhpStorm.
 * User: theob
 * Date: 10/01/2019
 * Time: 15:48
 */

namespace mywishlist\models;


class Reservation extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'reservation';
    protected $primaryKey = 'idReservation';
    public $timestamps = false;


}