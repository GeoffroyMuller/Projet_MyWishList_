<?php
/**
 * Created by PhpStorm.s
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

/**
 * Test: Liste les listes de souhaits
 */
echo "====Test: Lister les listes de souhaits===="."<br>";
$listes_des_souhaits = \mywishlist\models\Liste::select('user_id','titre','description','expiration')->get();
foreach ($listes_des_souhaits as $liste){
    echo $liste->no . ':'.$liste->user_id . ':' . $liste->titre . ':' . $liste->description . ':' . $liste->expiration."<br>";
}
echo "=============================="."<br>";


/**
 * theo
 * afficher un item avec l'id
 */
$item_recherche = \mywishlist\models\Item::select('*')->where('id','=',$_GET['pid'])->get() ;
foreach ($item_recherche as $item){
    echo $item->id . ':'. $item->liste_id . ':' . $item->nom . ':' . $item->descr .'<br>'.PHP_EOL;
}
echo "=============================="."<br>";

/* *
 * Test: Lister les items
 *
=======
/**
 * Test: Lister les items
>>>>>>> e94d8fc5a205ecb90260139b371cfd182a6899e2
 */
echo "====Test: Lister les items===="."<br>";
$res = \mywishlist\models\Item::get();
foreach ($res as $item){
    echo $item->id."|".$item->list_id."|".$item->nom."|".
        $item->descr."|".$item->img."|".$item->url."|".$item->tarif."<br>";
}
echo "=============================="."<br>";

$item = \mywishlist\models\Item::where("id","=","1")->first();
//var_dump($item);

$list = $item->liste()->first();
//echo "\n===========================";
//var_dump($list);

$items = $list->items()->get();
var_dump($items);


?>