<?php
/**
 * Created by PhpStorm.
 * User: Lucas
 * Date: 19/11/2018
 * Time: 15:32
 */
require_once __DIR__ . '/vendor/autoload.php';
use Illuminate\Database\Capsule\Manager as DB;

$db = new DB();
$db->addConnection(parse_ini_file('./src/conf/conf.ini'));
$db->setAsGlobal();
$db->bootEloquent();
/*
 * Liste les listes de souhaits
 */
$listes_des_souhaits = \mywishlist\models\Liste::select('user_id','titre','description','expiration')->get();
foreach ($listes_des_souhaits as $liste){
    echo $liste->no . ':'.$liste->user_id . ':' . $liste->titre . ':' . $liste->description . ':' . $liste->expiration.PHP_EOL;
}






?>