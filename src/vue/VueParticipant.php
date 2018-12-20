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

    public $selecteur;

    /**
     * VueParticipant constructor.
     * @param $tabAffichage
     */
    public function __construct($tabAffichage,$selecteur)
    {
        $this->elements = $tabAffichage;
        $this->selecteur = $selecteur;
    }

    /**
     * Génére le code html de l'affichage des listes de souhait
     */
    private function htmlListesDeSouhait(){
        $html=<<<END
<!--Content-->
            <header class="header-card">
                <h1>Découvrer des WishLists !</h1>
                <hr>
            </header>
END;
        foreach ($this->elements as $element){
            $html=$html.<<<END
            <div class="card">
                <header>
                    <img src="../../img/list_icon.png">
                    <p>$element->titre</p>
                    <hr>
                </header>
                <p class="card_exp">Exp : $element->expiration</p>
                <article>
                    <p>$element->description</p>
                </article>
                <a href="#"><button class="card-button" type="button"> Voir les items !</button></a>
            </div>
END;
        }
        return $html;
    }

    /**
     * Génére le code html de l'affichage d'une liste et de ces items
     */
    private function htmlItemsListe()
    {

        //En tête contenant les informations de la listes actuelle
        $html = <<<END
           <!--Content-->
    <header class="header-card">
        <h1>Titre de la liste</h1>
        <hr>
    </header>
<div class="conteneur-item">
END;

        foreach ($this->elements as $element){
            $html = <<<END
             <div class="card-item">
        <header>
            <img class="item-picture" src="../../img/$element->img">
            <p>$element->nom</p>
            <hr>
        </header>
        <p class="card_exp">Prix : $element->tarif</p>
        <article>
            <p>$element->descr</p>
        </article>
        <a href="#"><button class="card-button" type="button"> Réserver l'item !</button></a>
    </div>
END;
        }
        return $html."</div>";
    }

    /**
     * Génére le code html correspondant à l'affichage d'un Item
     */
        private function htmlItem(){
            $html="";
            try{
                $nom = $this->elements->nom;
                $description = $this->elements->descr;
                $nomImage = $this->elements->img;

                $html = <<<END
            
            <div class="container">
        <header class="header-card titre-item">
            <h1>$nom</h1>
            <hr>
        </header>

        <!--Component-->
        <div class="composantItem">
            <img class="item-image" src="/img/$nomImage">
            <h2 class="titre-description-item">Description</h2>
            <hr>
            <p class="description-item">
                $description
            </p>
            <h2 class="titre-status-item">Status</h2>
            <hr>
            <p class="status-item rouge">
                Non reservé ! (WIP)
            </p>
            <h2 class="titre-form-reserve">Reserver cet item !</h2>
            <hr>

            <form class="form-reserve" action="#" method="POST">
                <div class="form-nom">
                    <label for="nomParticipantInput">Votre nom :</label>
                    <input type="text" name="nomParticipant" id="nomParticipantInput" placeholder="Votre nom" inputmode="text" required>
                </div>
                <div class="form-message">
                    <label for="messageInput">Ajouter un message (optionel) :</label>
                    <textarea name="message" id="messageInput" rows="10" cols="40" placeholder="Votre message"></textarea>
                </div>

                <input class="form-submit" type="submit" value="Reserver">

            </form>



        </div>
    </div>
END;
            }catch(\ErrorException $exception){
                $html = <<<END
<header class="header-card titre-item">
            <h1>ERREUR : L'item demandé n'existe pas</h1>
            <hr>
        </header>
END;


            }


            return $html;
        }

        public function render(){
            switch($this->selecteur){
                case 'LIST_VIEW' :
                    $content = $this->htmlListesDeSouhait();
                    $homepage = <<<END
            <div id="slider">
                <figure>
                    <img src="../../img/pic1_carousel.png">
                    <img src="../../img/pic2_carousel.png">
                    <img src="../../img/pic3_carousel.png">
                    <img src="../../img/pic1_carousel.png">
                 </figure>
            </div>
END;

                    break;

                case 'LIST_ITEMS' :
                    $content = $this->htmlItemsListe();
                    $homepage="";
                    break;

                case 'ITEM' :
                    $content = $this->htmlItem();
                    $homepage="";
                    break;
            }
            $html=<<<END
        <!DOCTYPE html>
        <html lang="fr">
            <head>
                <title>WishList !</title>
                <meta charset="UTF-8">
                <link href="../../css/style.css" rel="stylesheet">
            </head>
            <body>
            <!--Topbar-->

            <div class="topbar-container">

                    <h1 class="titleTB">WishList</h1>
                    
                    <div class="menu">
                        <ul>
                            <li><a href="#">Les WishLists</a><hr class="menu_separator"></li>
                            <li><a href="#">Mes Listes</a><hr class="menu_separator"></li>
                            <li class="user"><a href="#">Vous n'êtes pas connecté !</a></li>
                        </ul>
                    </div>
            </div>

            <!--Head-->
            $homepage
            $content

</body><html>
END;
            echo $html;
        }




}