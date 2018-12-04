<?php
/**
 * Created by PhpStorm.
 * User: Lucas
 * Date: 04/12/2018
 * Time: 11:13
 */

namespace mywishlist\vue;


class VueParticipant
{
    /**
     * @var $elements
     *      Tableau des éléments à afficher
     */
    public $elements;

    /**
     * VueParticipant constructor.
     * @param $tabAffichage
     */
    public function __construct($tabAffichage)
    {
        $this->elements = $tabAffichage;
    }

    /**
     * Génére le code html de l'affichage des listes de souhait
     */
    private function htmlListesDeSouhait(){
        $html="";
        foreach ($this->elements as $element){
            $html=$html.<<<END
            
        <p>$this->element->titre</p>
        <p>$this->element->description</p>
        <p>$this->element->expiration</p>
END;
        }
        return '<section>'.$html.'</section>';
    }

    /**
     * Génére le code html de l'affichage d'une liste et de ces items
     */
    private function htmlItemsListe()
    {
        //En tête contenant les informations de la listes actuel
        $html = <<<END
        <p>$this->elements[0]->titre</p>
        <p>$this->elements[0]->description</p>
        <p>$this->elements[0]->expiration</p>
END;
        //On génére le code html pour chaque item
        for ($i=1; $i<sizeof($this->elements);$i++) {
            $html = <<<END
            
            <p>$this->elements[i]->nom</p>
            <p>$this->elements[i]->descr</p>
            <img src="web/img/$this->elements[i]->img">
            <p>$this->elements[i]->tarif</p>
END;

        }
        return $html;
    }

    /**
     * Génére le code html correspondant à l'affichage d'un Item
     */
        private function htmlItem(){

            $html = <<<END
            
            <p>$this->elements[0]->nom</p>
            <p>$this->elements[0]->descr</p>
            <img src="web/img/$this->elements[0]->img">
            <p>$this->elements[0]->tarif</p>
END;
            return $html;
        }

        


}