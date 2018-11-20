<?php
/**
 * Created by PhpStorm.
 * User: Lucas
 * Date: 19/11/2018
 * Time: 15:32
 */
require_once __DIR__ . '/vendor/autoload.php';

$listes_des_souhaits = \mywishlist\models\Liste::select('*')->get();

/**
 * theo
 * afficher un item avec l'id
 */
$item_recherche = \mywishlist\models\Item::select('*')->where('id','equal','1')->get() ;

?>