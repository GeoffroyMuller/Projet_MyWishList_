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
//$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAsGlobal();
$db->bootEloquent();
$listes_des_souhaits = \mywishlist\models\Liste::select('user_id','titre','description','expiration')->get();
foreach ($listes_des_souhaits as $liste){
    echo $liste->no . ':'.$liste->user_id . ':' . $liste->titre . ':' . $liste->description . ':' . $liste->expiration.PHP_EOL;
}


/**
 * theo
 * afficher un item avec l'id
 */
$item_recherche = \mywishlist\models\Item::select('*')->where('id','=',$_GET['pid'])->get() ;
foreach ($item_recherche as $item){
    echo $item->id . ':'. $item->liste_id . ':' . $item->nom . ':' . $item->descr .'<br>'.PHP_EOL;
}
?>