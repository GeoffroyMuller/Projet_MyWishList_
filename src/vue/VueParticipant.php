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

    public $app;

    /**
     * VueParticipant constructor.
     * @param $tabAffichage
     */
    public function __construct($tabAffichage,$selecteur)
    {
        $this->elements = $tabAffichage;
        $this->selecteur = $selecteur;
        $this->app = \Slim\Slim::getInstance();
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
            $lienVersItems = $this->app->urlFor("afficherItemsListe",["id"=>$element->no]);
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
                <a href="$lienVersItems"><button class="card-button" type="button"> Voir les items !</button></a>
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
        $liste = $this->elements['liste'];
        //En tête contenant les informations de la listes actuelle
        $html = <<<END
           <!--Content-->
    <header class="header-card">
        <h1>$liste->titre</h1>
        <hr>
    </header>
END;

        foreach ($this->elements['items'] as $element){
            $url = $this->app->urlFor("afficherItem",['id'=>$element->id]);
            $html = $html.<<<END
            <div class="card-item-liste">
            <header>
                <p>$element->nom - $element->tarif €</p>
                <hr>
            </header>
            <article>
                <img class="item-card-image" src="/img/$element->img">
                <p class="description-card-item">$element->descr</p>
            </article>
            <a href="$url"><button class="card-button" type="button"> Voir l'item !</button></a>
        </div>
END;
        }
        return '<div class="container-items-liste">'.$html.'</div>';
    }

    /**
     * Génére le code html correspondant à l'affichage d'un Item
     */
        private function htmlItem(){
            $html="";
            try{
                $id = $this->elements['item']->id;
                $nom = $this->elements['item']->nom;
                $description = $this->elements['item']->descr;
                $nomImage = $this->elements['item']->img;
                $urlButton=$this->app->urlFor("modifierItem",['id'=>$id]);


                $html = <<<END
            
        <div class="container">
        <header class="header-card titre-item">
            <h1>$nom</h1>
            <hr>
            <div class="item-modifier-bouton">
                <a href="$urlButton"><button>Modifier l'item</button></a>
            </div>
        </header>

        <!--Component-->
                <div class="composantItem">
            <img class="item-image" src="/img/$nomImage">
            <h2 class="titre-description-item">Description</h2>
            <hr>
            <p class="description-item">
                $description
            </p>
            <h2 class="titre-images-item">Images</h2>
            <hr>


            <div class="images-item">
END;
                if(!is_null($this->elements['images'][0])){
                    foreach ($this->elements['images'] as $image){
                        $html=$html.<<<END
 <div class="image">
                    <img src="/img/$image->nom">
                </div>
END;
                    }
                }

                $html=$html.<<<END
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
                echo $exception;
                $html = <<<END
<header class="header-card titre-item">
            <h1>ERREUR : L'item demandé n'existe pas</h1>
            <hr>
        </header>
END;


            }


            return $html;
        }

        private function htmlItemModification(){
            $nom = $this->elements['item']->nom;
            $descr= $this->elements['item']->descr;
            $id = $this->elements['item']->id;
            $tarif = $this->elements['item']->tarif;
            $nomImage = $this->elements['item']->img;
            $urlModifierImage = $this->app->urlFor('modifierImageItem',['id'=>$id]);

            $urlSubmit = $this->app->urlFor('application-modification',['id'=>$id]);

            $html = <<<END
<div class="container">
        <form enctype="multipart/form-data" class="form-modification-item" action="$urlSubmit" method="POST" >
            <header class="header-card titre-item">
                <label class="label-nom-item" for="titre-item-modification-input">Nom de l'item :</label>
                <input type="text" name="titre-item-modification" id="titre-item-modification-input" inputmode="text"
                    value="$nom">
                <hr>
            </header>

            <!--Component-->
            <div class="composantItem">
                <div class="container-image-item item-image">
                    <img  class="image-principale" src="/img/$nomImage">
                    <label for="item-image-modification">Modifier l'image de l'item :</label>
                    <input type="file" name="itemImageModification" id="item-image-modification"  accept="image/png, image/jpeg, image/jpg">

                </div>
                <h2 class="titre-description-item">Editer la Description</h2>
                <hr>
                <div class="description-item">
                    <textarea class="description-item-modification" name="description-item-modification" id="description-item-modification-input"
                        rows="20" cols="85">
$descr</textarea>
                    <h2 class="titre-images-item">Images</h2>
                    <a href="$urlModifierImage">Modifier les images</a>

                    <hr>
                    


                    <div class="images-item">
END;
            if(!is_null($this->elements['images'][0])) {
                foreach ($this->elements['images'] as $image) {
                    $html = $html . <<<END
                <div class="image">
                            <img src="/img/$image->nom">
                            <a href="#"><button>Supprimer</button></a>
                        </div>
                
END;
                }
            }
            $html=$html.<<<END
                    </div>
                    <input class="modification-submit" type="submit" value="Enregistrer les modifications">
                </div>



        </form>
        </div>
    </div>
END;

            return $html;
}

    /**
     * Génére le code html correspondant à la page de modificatioon des images d'un item
     */
        private function htmlImageModification(){
            $item = $this->elements['item'];
            $imageUtilisees = $this->elements['imagesUtilise'];
            $imageProposees = $this->elements['imageProposees'];
            $i=0;
            $urlSubmit = $this->app->urlFor('appModifIMage',['id'=>$item->id]);

            $html=<<<END
    <div class="container">
     <form enctype="multipart/form-data" class="form-modification-item" action="$urlSubmit" method="POST">
        <header class="header-card titre-item">
            <h1>$item->nom</h1>
            <hr>
        </header>
        <!--Component-->
        <div class="composantItem">
            <h2 class="titre-images-item">Images - Actuellement utilisées</h2>
            <hr>
<div class="images-item">
END;
            //Images utilisees ($image contient le nom de l'image directement)
            foreach($imageUtilisees as $image){
                $html=$html.<<<END
 <div class="image">
                    <img class="image-modification" src="/img/$image->nom">
                    <input id="input-supp$i" type="checkbox" name="del[]" value="$image->id" />
                    <label class="label-supp" for="input-supp$i">Supprimer</label>
                </div>
END;
                $i++;
            }
            $html=$html.<<<END
 <h2 class="titre-images-item">Images - Proposées</h2>
                <hr>
<div class="images-item">
END;

            //Images proposées ($image contient le nom de l'image directement)
            foreach ($imageProposees as $image){
                $html=$html.<<<END
 <div class="image">
                        <img class="image-modification" src="/img/$image->nom">
                        <input id="input-supp$i" type="checkbox" name="add[]" value="$image->id" />
                        <label class="label-supp" for="input-supp$i">Ajouter</label>
                    </div>
END;
                $i++;
            }

            $html=$html.<<<END
 </div>
                <label for="item-image-modification">Importer des images :</label>
                <input type="file" id="item-image-modification" name="nouvellesImagesItem" accept="image/png, image/jpeg, image/jpg">
                <input class="form-submit-images" type="submit" value="Enregistrer les modifications">

                </form>



            </div>
        </div>

END;

return $html;
        }

        public function render(){
            $homepage="";
            $content="";
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
                    break;

                case 'ITEM' :
                    $content = $this->htmlItem();
                    break;

                case 'ITEM_MODIFICATION':
                $content = $this->htmlItemModification();
                break;

                case 'IMAGE_MODIFICATION':
                    $content = $this->htmlImageModification();
                    break;
            }
            $urlTopBarListes = $this->app->urlFor("listes");
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

                    <h1 class="titleTB"><span>Wish</span>List</h1>
                    
                    <div class="menu">
                        <ul>
                            <li><a href="$urlTopBarListes">Les <span>Wish</span>Lists</a><hr class="menu_separator"></li>
                            <li><a href="#">Mes Listes</a><hr class="menu_separator"></li>
                            <li class="user"><a href="#">Vous n'êtes pas connecté !</a></li>
                        </ul>
                    </div>
            </div>

            <!--Head-->
            $homepage
            $content

</body></html>
END;
            echo $html;
        }




}