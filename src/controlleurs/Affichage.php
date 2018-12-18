<?php
/**
 * Created by PhpStorm.
 * User: Lucas
 * Date: 03/12/2018
 * Time: 15:14
 */

namespace mywishlist\controlleurs;


class Affichage
{
    public function itemSListe($noliste){
        echo "====Test: Lister les items d'une liste===="."<br>";
        $res = \mywishlist\models\Liste::where("no","=",$noliste);
        echo $res->titre;
        echo "=============================="."<br>";
    }
}