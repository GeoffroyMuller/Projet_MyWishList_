<?php
/**
 * Created by PhpStorm.
 * User: Lucas
 * Date: 19/11/2018
 * Time: 15:32
 */
require_once __DIR__ . '/vendor/autoload.php';

//$listes_des_souhaits = Liste::select('*')->get();



/**
 * Geoffroy
 * Test: lister les items
 */
echo "Test: lister les items"."<br>";
$res = \mywishlist\models\Item::get();
foreach ($res as $item){
    echo $item->id." ".$item->list_id." ".$item->nom." ".
        $item->descr." ".$item->img." ".$item->url." ".$item->tarif;
}

?>