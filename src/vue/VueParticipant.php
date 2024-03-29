<?php
/**
 * Created by PhpStorm.
 * User: Lucas
 * Date: 04/12/2018
 * Time: 11:13
 */

namespace mywishlist\vue;


use mywishlist\controlleurs\ControleurInternaute;
use mywishlist\controlleurs\Createur;
use mywishlist\models\Liste;
use mywishlist\models\Reservation;
use mywishlist\models\Utilisateur;

class VueParticipant
{
    /**
     * @var $elements
     *      Tableau des éléments à afficher
     */
    private $elements;

    private $selecteur;

    private $app;

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
            if($element->privee === 0){
                $lienVersItems = $this->app->urlFor("afficherItemsListe",["id"=>$element->no]);
                $html=$html.<<<END
            <div class="card">
                <header>
                    <img src="../../img/list_icon.png" alt="Icone d'une liste">
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

        }
        return $html;
    }

    /**
     * Génére le code html de l'affichage d'une liste et de ces items
     */
    private function htmlItemsListe()
    {
        $liste = $this->elements['liste'];
        $urlRendrePublic = $this->app->urlFor('rendrePublique',['id'=>$liste->no]);

        //On affiche le bouton rendre publique seulement si la liste est privée
        if($liste->privee === 1){
            if(isset($this->elements['items'])){
                $boutonPublique = <<<END
            <h1 class="titre-page-liste">$liste->titre</h1>
        <a href="$urlRendrePublic"><button class="bouton-rendre-publique">Rendre publique</button></a>
END;
            }else{
                $boutonPublique = <<<END
            <h1 class="titre-page-liste flottantGauche">$liste->titre - Cette liste n'a pas d'item</h1>
        <a href="$urlRendrePublic"><button class="bouton-rendre-publique">Rendre publique</button></a>
END;
            }

        }else{
            if(isset($this->elements['items'])){
                $boutonPublique=<<<END
            <h1 class="titre-page-liste">$liste->titre</h1>
END;
            }else{
                $boutonPublique=<<<END
            <h1 class="titre-page-liste">$liste->titre - Cette liste n'a pas d'item</h1>
END;
            }

        }

        //Vérification du créateur
        $urlCreeItem = $this->app->urlFor('creationItemPage',['id'=>$liste->no]);
        $urlModifierListe = $this->app->urlFor('modifierInfoListe',['id'=>$liste->no]);
        if(\mywishlist\controlleurs\Createur::verifierLeProprietaireDeLaListe($liste->no)){
            $boutonAjouterItem=<<<END
            <a href="$urlCreeItem"><button class="bouton-rendre-publique yellow">Ajouter un item</button></a>
            <a href="$urlModifierListe"><button class="bouton-rendre-publique">Modifier la liste</button></a>
END;

        }else{
            $boutonAjouterItem="";
        }


        //En tête contenant les informations de la listes actuelle
        $token = Liste::where('no','=',$liste->no)->first();
        $urlToken = \Slim\Slim::getInstance()->urlFor('afficherListeAvecToken',['token'=>$token->token]);

        if(isset($_SESSION['profile']) && Createur::verifierLeProprietaireDeLaListe($liste->no)){
            $tokenHtml=<<<END
<p class="affichage-token">Token : $urlToken </p>
END;

        }else{
            $tokenHtml="";
        }

        $html = <<<END
           <!--Content-->
    <header class="header-card">
        
        $boutonPublique
        $boutonAjouterItem
        $tokenHtml
        <hr>
  
    </header>
END;



        if(isset($this->elements['items'])){

            foreach ($this->elements['items'] as $element){
                $url = $this->app->urlFor("afficherItem",['id'=>$element->id]);
                $urlSupp = $this->app->urlFor("supprimerItem",['idlist'=>$liste->no,'id'=>$element->id]);

                $reserve = \mywishlist\controlleurs\Createur::verifierLaReservationItem($element->id);
                if(!$reserve){
                    $couleurStatus = 'rouge';
                    $texteStatus = 'Non reservé';
                }else{
                    $couleurStatus = 'vert';
                    $texteStatus = 'Reservé';
                }
                
                

                $html = $html.<<<END
            <div class="card-item-liste">
            <header>
                <p>$element->nom - $element->tarif € - <span class="$couleurStatus">$texteStatus</span></p>
                <hr>
            </header>
            <article>
                <img class="item-card-image" src="../../img/$element->img" alt="image d'un item">
                <p class="description-card-item">$element->descr</p>
            </article>
            <div class="container-bouton-item">
            <a href="$url"><button class="card-button" type="button"> Voir l'item !</button></a>

END;
                if(!$reserve && isset($_SESSION['profile']) && Createur::verifierLeProprietaireDeLaListe($liste->no,$liste->token)){
                    $html=$html.<<<END
             <a href="$urlSupp"><button class="card-button button-del" type="button"> Supprimer l'item !</button></a>
            </div>
        </div>
END;
                }else{
                    $html=$html.<<<END
            </div>
        </div>
END;

                }
            }


        }
        //Partie commentaire
        $html=$html.<<<END
            <header class="header-card">
        <h1 class="titre-page-liste">Commentaires</h1>
        <hr>
    </header>
 <div class="container-commentaire">
        <div class="wrapper">
END;
        if(isset($this->elements['commentaires'])){

            $commentaires = $this->elements['commentaires'];

            foreach($commentaires as $commentaire){
                $pseudoPersonne = \mywishlist\models\Utilisateur::where('idUser','=',$commentaire->user_id)->first();
                $pseudoPersonne = $pseudoPersonne->uName;

                $html=$html.<<<END

            <div class="composant-commentaire">
                <div class="pseudo-commentaire">$pseudoPersonne</div>
                <p class="contenu-commentaire">
                   $commentaire->commentaire
                </p>
            </div>

END;

            }

        }

        $idListe = $liste->no;
        $urlAjoutComm=$this->app->urlFor('ajoutCommentaire');

        $html=$html.<<<END
            <div class="ajouter-commentaire">
                <h2 class="title-commentaire">Ajouter un Commentaire :</h2>

                <form action="$urlAjoutComm" method="post">
                    <textarea name="commentaire" rows="10" cols="98" placeholder="Votre commentaire ici..."></textarea>
                    <input class="button-send-commentaire" type="submit" value="Envoyer le commentaire">
                    <input class="cacher" name="idListe" type="text" value="$idListe">
                  </form> 

            </div>
        </div>
    </div>
END;

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




            //Si l'utilisateur est connecté et si l'item n'est pas déja reserve alors on affiche le formulaire et on test l'affichage du bouton de modification
            if(isset($_SESSION['profile']) ){
                //Si l'utilisateur posséde l'item alors le bouton de modification s'affiche
                if(ControleurInternaute::testerAppartenanceItem($id) &&
                    (!\mywishlist\controlleurs\Createur::verifierLaReservationItem($id))){
                    $modifBouton=<<<END
            <div class="item-modifier-bouton">
                <a href="$urlButton"><button>Modifier l'item</button></a>
            </div>
END;

                }else{
                    $modifBouton="";
                }



                $username=$_SESSION['profile']['username'];
                $urlsubmitReserv = $this->app->urlFor('reserverItem');

                if(!\mywishlist\controlleurs\Createur::verifierLaReservationItem($id)){
                    $form=<<<END
                            <h2 class="titre-form-reserve">Reserver cet item !</h2>
            <hr>
  <form class="form-reserve" action="$urlsubmitReserv" method="POST">
                <div class="form-nom">
                    <label for="nomParticipantInput">Votre nom :</label>
                    <input type="text" name="nomParticipant" id="nomParticipantInput" value="$username" inputmode="text" required>
                </div>
                <div class="form-message">
                    <label for="messageInput">Ajouter un message (optionel) :</label>
                    <textarea name="message" id="messageInput" rows="10" cols="40" placeholder="Votre message"></textarea>
                </div>
                
                <input class="cacher" type="text" value="$id" name="idItem">
                <input class="form-submit" type="submit" value="Reserver">

            </form>
END;
                }else{
                    $form="";
                }


            }else{
                $form="";
                $modifBouton="";
            }

            $html = <<<END
            
        <div class="container">
        <header class="header-card titre-item">
            <h1>$nom</h1>
            <hr>
            $modifBouton
        </header>

        <!--Component-->
                <div class="composantItem">
            <img class="item-image" src="../../img/$nomImage" alt="image d'un item">
            <h2 class="titre-description-item">Description</h2>
            <hr>
            <p class="description-item">
                $description
            </p>



            <div class="images-item">
END;
            if(!is_null($this->elements['images'][0])){
                $html=$html.<<<END
<h2 class="titre-images-item">Images</h2>
            <hr>
END;
                foreach ($this->elements['images'] as $image){
                    $html=$html.<<<END
 <div class="image">
                    <img src="../../img/$image->nom" alt="image de l'item">
                </div>
END;
                }
            }

            //On vérifie si l'item est réservé
            if(\mywishlist\controlleurs\Createur::verifierLaReservationItem($id)){

                $nomParticipant=Reservation::where('idItem','=',$id)->first();
                $nomParticipant = $nomParticipant->idUser;
                $nomParticipant = Utilisateur::where('idUser','=',$nomParticipant)->first();
                $nomParticipant = $nomParticipant->uName;

                //Si le créateur consulte la page
                $liste = $this->elements['item']->liste()->first();

                //On vérifie si la liste appartient à l'utilisateur
                //La méthode utiliser dans la condition vérifie également les cookies.
                if(Createur::verifierLeProprietaireDeLaListe($liste->no,$liste->token)){
                    //L'utilisateur est le propriétaire de la liste
                    $dateDuJour = date("Y-m-d");
                    $dateExpListe = $liste->expiration;
                    //Si la liste a expiré alors on affiche lemessage de reservation et le nom du participant
                    if($dateDuJour>$dateExpListe){
                        $messageReservation = Reservation::where('idItem','=',$id)->first();
                        $messageReservation = $messageReservation->message;
                        $html=$html.<<<END
 <h2 class="titre-status-item">Status</h2>
            <hr>
            <p class="status-item vert">
                Reservé ! - Par $nomParticipant
            </p>
            <p class="status-item">Message de reservation : $messageReservation</p>


          $form



        </div>
    </div>

END;
                    }else{
                        $html=$html.<<<END
 <h2 class="titre-status-item">Status</h2>
            <hr>
            <p class="status-item vert">
                Reservé !
            </p>


          $form



        </div>
    </div>

END;
                    }

                }else{
                    $html=$html.<<<END
 <h2 class="titre-status-item">Status</h2>
            <hr>
            <p class="status-item vert">
                Reservé ! - Par $nomParticipant
            </p>


          $form



        </div>
    </div>

END;
                }

            }else{//L'item n'est pas réservé
                $html=$html.<<<END
 <h2 class="titre-status-item">Status</h2>
            <hr>
            <p class="status-item rouge">
                Non reservé !
            </p>


          $form



        </div>
    </div>

END;
            }


        }catch(\OutOfBoundsException $exception){
            //$this->app->redirect($this->app->urlFor('erreur',['msg'=>"L'item demandé n'existe pas"]));
        }


        return $html;
    }

    /**
     * Génére le code html de modification de l'item
     * @return string
     */
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
                    <img  class="image-principale" src="/img/$nomImage" alt="Image principal d'un item">
                    <label for="item-image-modification">Modifier l'image de l'item :</label>
                    <input type="file" name="itemImageModification" id="item-image-modification"  accept="image/png, image/jpeg, image/jpg">

                </div>
                
                <div class="tarifModification">
                    <label for="expListe">Tarif :</label>
                    <input type="number" id="expListe" name="tarifItem" min="0" value="$tarif" required>
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
                            <img src="/img/$image->nom" alt="Image d'un item">
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
        if(!is_null($imageUtilisees)){
            foreach($imageUtilisees as $image){
                $html=$html.<<<END
 <div class="image">
                    <img class="image-modification" src="/img/$image->nom" alt="Image utilisee par l'item">
                    <input id="input-supp$i" type="checkbox" name="del[]" value="$image->id" />
                    <label class="label-supp" for="input-supp$i">Supprimer</label>
                </div>
END;
                $i++;
            }
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
                        <img class="image-modification" src="/img/$image->nom" alt="Image proposée">
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

    /**
     * Génére le code html de la page d'inscription
     * @return string
     */
    private function htmlInscription(){
        $urlSubmit = $this->app->urlFor('inscriptionprocess');
        $urlConnexion = $this->app->urlFor('connexion');
        $html = <<<END
    <!--Content-->
    <div class="container">
        <header class="header-card titre-item">
            <h1>Inscription</h1>
            <hr>
        </header>

        <!--Component-->
            <form class="form-identification" action="$urlSubmit" method="POST">

                <label for="username">Nom d'utilisateur :</label>
                <input id="username" type="texte" name="username">

                <label for="password">Mot de passe :</label>
                <input id="password" type="password" name="password">
                <a href="$urlConnexion">Déja un compte ?</a>

                <input id="submit-identification" type="submit" value="S'inscrire">
            </form>

    </div>
END;
        return $html;

    }

    /**
     * Génére le code html de la page de connexion
     * @return string
     */
    private function htmlConnexion(){
        $urlSubmit = $this->app->urlFor('connexionprocess');
        $urlPasDeCompte = $this->app->urlFor('inscription');
        $html = <<<END
    <!--Content-->
    <div class="container">
        <header class="header-card titre-item">
            <h1>Se connecter</h1>
            <hr>
        </header>

        <!--Component-->
            <form class="form-identification" action="$urlSubmit" method="POST">

                <label for="username">Nom d'utilisateur :</label>
                <input id="username" type="texte" name="username">

                <label for="password">Mot de passe :</label>
                <input id="password" type="password" name="password">
                <a href="$urlPasDeCompte">Pas de compte ?</a>

                <input id="submit-identification" type="submit" value="Se connecter">


            </form>

    </div>
END;

        return $html;
    }


    /**
     * Génére le code html de la page du profil
     * @return string
     */
    private function htmlProfil(){
        $urlDeco = $this->app->urlFor('deconnexion');
        $urlSupp = $this->app->urlFor('suppCompte');
        $username = $this->elements['uName'];

        $urlModifierProfil = $this->app->urlFor('profilModif');

        $html=<<<END
 <div class="container">
        <header class="header-card titre-item">
            <h1 class="titre-profil">Votre profil</h1>
            <a href="$urlModifierProfil">Modifier mon profil</a>
            <hr>
        </header>

        <!--Component-->
        <div class="container-composant-profil">
            <img class="avatar-profil" src="/img/logo_employee_resize.jpg" alt="avatar du profil">
            <p><span>Nom d'utilisateur : </span>$username</p>
            
            <div class="container-boutons">
                <a href="$urlDeco"><button class="deconnexion">Deconnexion</button></a>
                <a href="$urlSupp"><button class="supp-compte">Supprimer mon compte</button></a>
            </div>
            <h2 class="titre-images-item">Vos listes</h2>
            <hr>
END;
        if(isset($this->elements['listes'])){
            foreach ($this->elements['listes'] as $liste){
                $urlListe= $this->app->urlFor('afficherItemsListe',['id'=>$liste->no]);
                if($liste->privee == 1){
                    $type = 'privee';
                }else{
                    $type = 'publique';
                }
                $html=$html.<<<END
            <div class="liste-preview $type">
                <a href="$urlListe">
                <img src="/img/list_icon.png" alt="Icone d'une liste">
                <h3>$liste->titre</h3>
                </a>
            </div>
END;

            }
        }




        return $html;
    }

    /**
     * Génére le code html de la page de modification du profil
     */
    public function htmlProfilModification(){
        $urlProfil = $this->app->urlFor('profil');
        $urlProfilEnregistrerModification = $this->app->urlFor('enregistrerProfil');
        $html=<<<END
  <div class="container">
        <header class="header-card titre-item">
            <h1 class="titre-profil">Votre profil</h1>
            <a href="$urlProfil">Revenir au profil</a>
            <hr>
        </header>

        <!--Component-->
        <div class="container-composant-profil">
            <img class="avatar-profil" src="/img/logo_employee_resize.jpg" alt="avatar du profil">
            <form class="form-modification-profil" action="$urlProfilEnregistrerModification" method="POST">

                <div class="container-input-profil">
                    <label for="profil-username-modification">Nom d'utilisateur :</label>

                    <input type="text" id="profil-username-modification" name="profil-username-modification">
                </div>

                <div class="container-input-profil password-input-profil">
                    <label for="profil-pass-modification">Mot de passe :</label>

                    <input type="password" id="profil-pass-modification" name="profil-pass-modification">
                </div>
                            <div class="container-boutons">
                <input type="submit" value="Enregistrer les modifications"  class="deconnexion"></input>
            </div>
            </form>




        </div>

    </div>
END;

        return $html;
    }

    /**
     * Génére le code html de la page de creation d'un item
     * @return string
     */
    public function htmlCreationItem(){
        $idDeLaListe = $this->elements;
        $urlpourCreationitemProcess = $this->app->urlFor('creationItem',['id'=>$idDeLaListe]);
        $html=<<<END
 <div class="container">
        <header class="header-card titre-item">
            <h1>Créer un item</h1>
            <hr>
        </header>

        <!--Component-->
        <div class="container-creation-liste">
            <form class="form-creation-liste" action="$urlpourCreationitemProcess" method="POST" enctype="multipart/form-data">
                <div class="image-creation-item">
                    <img src="/img/placeholder-creation-liste.gif" alt="Placeholder">

                    <label for="nomListe">Image principale de l'item :</label>
                    <input type="file" id="item-image-creation" name="item-image-creation" accept="image/png, image/jpeg, image/jpg">

                </div>

                <label for="nomItem">Nom de l'item :</label>
                <input id="nomItem" type="texte" name="nomItem" placeholder="Mon Item" required>

                <label for="descrItem">Description :</label>
                <textarea id="descrItem" cols="50" rows="10" name="descrItem" placeholder="Je créer cette item pour..." required></textarea>

                <label for="expListe">Tarif :</label>

                <input type="number" id="expListe" name="prixItem" min="0" required>


                <input id="submit-creation-liste" type="submit" value="Créer l'item !">


            </form>
        </div>

    </div>

END;

        return $html;
    }

    /**
     * Génére le code html de la page de creation d'une liste
     */
    public function htmlCreationListe(){
        $date = date('Y-m-d');
        $urlCreerListeCreateur = $this->app->urlFor('creationListe');
        $html=<<<END
    <div class="container">
        <header class="header-card titre-item">
            <h1>Créer une liste</h1>
            <hr>
        </header>

        <!--Component-->
        <div class="container-creation-liste">
            <form class="form-creation-liste" action="$urlCreerListeCreateur" method="POST">

                <label for="nomListe">Nom de la liste :</label>
                <input id="nomListe" type="texte" name="nomListe" placeholder="Ma liste" required>

                <label for="descrListe">Description :</label>
                <textarea id="descrListe" cols="50" rows="10" name="descrListe" placeholder="Je créer cette liste pour..." required></textarea>

                <label for="expListe">Date d'expiration:</label>

                <input type="date" id="expListe" name="expListe" value="$date" min="$date" required>

                <input type="checkbox" id="publiqueListe" name="publiqueListe">
                <label for="publiqueListe">Rendre publique</label>

                 <input id="submit-creation-liste" type="submit" value="Créer la liste !">


            </form>
        </div>

    </div>
END;

        return $html;
    }

    /**
     * Génére le code html de la page "Mes Listes"
     * @return string
     */
    public function htmlMesListes(){
        $urlCreerListe = $this->app->urlFor('creationListePage');
        $urlVisualiseToken = $this->app->urlFor('afficherToken');

        $html=<<<END
 <div class="container">
        <header class="header-card titre-item">
            <h1 class="titre-liste">Vos Listes</h1>
            <a href="$urlCreerListe"><button class="bouton-rendre-publique">Créer une liste</button></a>
            <hr>
        </header>

        <!--Component-->
        <div class="container-composant-liste">
                    <div class="container-visualiser-token">
                    <h1 class="titre-liste-publique titre-images-item">Visualiser une liste avec un token</h1>
                    <hr>

                    <div class="composant-visualiser-token">
                        <form action="$urlVisualiseToken" method="post">
                                <label for="token">Token de la liste :</label>
                                <input type="text" name="token" id="token"><br>
                                <input class="submit-token" type="submit" value="Visualiser la liste">
                        </form>
                    </div>
            </div>
            <div class="container-liste-publique">
                <h1 class="titre-liste-publique titre-images-item">Listes publiques</h1>
                    <hr>


                    <div class="container-liste-preview-detail">
END;
        foreach($this->elements as $liste){
            if($liste->privee == 0){
                $urlListe = $this->app->urlFor('afficherItemsListe',['id'=>$liste->no]);
                $html=$html.<<<END
                        <a href="$urlListe">
                            <div class="liste-preview-detail publique">
                                <h3 class="liste-preview-detail-titre">$liste->titre</h3>
                                <p class="liste-preview-detail-exp">Exp : $liste->expiration</p>
                                <p class="liste-preview-detail-descr">$liste->description</p>
                            </div>
                        </a>
END;
            }


        }

        $html=$html.<<<END
 </div>
 </div>


                    <div class="container-liste-privee">
                        <h2 class="titre-liste-privee titre-images-item">Listes privée</h2>
                        <hr>


                        <div class="container-liste-preview-detail">
END;

        foreach($this->elements as $liste){
            if($liste->privee === 1){
                $urlListe = $this->app->urlFor('afficherItemsListe',['id'=>$liste->no]);
                $html=$html.<<<END
                        <a href="$urlListe">
                            <div class="liste-preview-detail privee">
                                <h3 class="liste-preview-detail-titre">$liste->titre</h3>
                                <p class="liste-preview-detail-exp">Exp : $liste->expiration</p>
                                <p class="liste-preview-detail-descr">$liste->description</p>
                            </div>
                        </a>
END;
            }


        }




        return $html;
    }

    /**
     * Génére le code html de la page d'erreur
     */
    public function htmlErreur(){
        $msgerreur = $this->elements;
        $html = <<<END
 <div class="container">
        <header class="header-card titre-item">
            <h1 class="titre-liste marge-top">Nous ne pouvons accéder à votre requête</h1>
 
        </header>

        <!--Component-->
        <div class="container-composant-liste">
            <div class="container-visualiser-token">
                    <div class="composant-visualiser-token">
                        <p class="message-erreur">$msgerreur</p>
                    </div>
            </div>
            

            </div>
END;
        return $html;

    }


    /**
     * Génére le code html de la page de modification d'une liste
     */
    public function htmlModifierListe(){
        $id = $this->elements;
        $liste = Liste::where('no','=',$id)->first();
        $nom = $liste->titre;
        $description = $liste->description;
        $expiration = $liste->expiration;
        $date = date('Y-m-d');
        $urlModifierListe = $this->app->urlFor('processmodifierInfoListe',['id'=>$id]);
        $html=<<<END
    <div class="container">
        <header class="header-card titre-item">
            <h1>Modifier une liste</h1>
            <hr>
        </header>

        <!--Component-->
        <div class="container-creation-liste">
            <form class="form-creation-liste" action="$urlModifierListe" method="POST">

                <label for="nomListe">Nom de la liste :</label>
                <input id="nomListe" type="texte" name="nomListe" value="$nom" required>

                <label for="descrListe">Description :</label>
                <textarea id="descrListe" cols="50" rows="10" name="descrListe" value="$description" required></textarea>

                <label for="expListe">Date d'expiration:</label>

                <input type="date" id="expListe" name="expListe" value="$expiration" min="$date" required>

                <input type="text" id="publiqueListe" name="idListe" class="cacher" value="$id">
                 <input id="submit-creation-liste" type="submit" value="Modifier la liste !">


            </form>
        </div>

    </div>
END;

        return $html;
    }

    public function render(){
        $homepage="";
        $content="";
        $connecte="Vous n'êtes pas connecté !";
        $linkProfile= $this->app->urlFor("profil");
        //Verification si utilisateur est connecté
        if(isset($_SESSION['profile'])){
            $uName = $_SESSION['profile']['username'];
            $connecte = "Bonjour, $uName !";
        }


        switch($this->selecteur){
            case 'LIST_VIEW' :
                $content = $this->htmlListesDeSouhait();
                $homepage = <<<END
            <div id="slider">
                <figure>
                    <img src="../../img/pic1_carousel.png" alt="Image caroussel">
                    <img src="../../img/pic2_carousel.png" alt="Image caroussel">
                    <img src="../../img/pic3_carousel.png" alt="Image caroussel">
                    <img src="../../img/pic1_carousel.png" alt="Image caroussel">
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

            case 'INSCRIPTION':
                $content = $this->htmlInscription();
                break;

            case 'CONNEXION':
                $content = $this->htmlConnexion();
                break;

            case 'PROFIL':
                $content = $this->htmlProfil();
                break;

            case 'PROFIL_MODIFICATION':
                $content = $this->htmlProfilModification();
                break;

            case 'ITEM_CREATION':
                $content = $this->htmlCreationItem();
                break;

            case 'LISTE_CREATION':
                $content = $this->htmlCreationListe();
                break;

            case 'MES_LISTES':
                $content = $this->htmlMesListes();
                break;

            case 'ERREUR':
                $content = $this->htmlErreur();
                break;
            case 'MODIFIER_LISTE':
                $content = $this->htmlModifierListe();
                break;
        }
        $urlTopBarListes = $this->app->urlFor("listes");
        $urlTopBarMesListes = $this->app->urlFor("mesListes");
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
                            <li><a href="$urlTopBarMesListes">Mes Listes</a><hr class="menu_separator"></li>
                            <li class="user"><a href="$linkProfile">$connecte</a></li>
                        </ul>
                    </div>
            </div>

            <!--Head-->
            $homepage
            $content
            </div>
            </div>
            </div>
        
<footer class="pagefooter">

        <div class="footer-left">

            <h3>Mem<span>bres</span></h3>

            <div class="footer-members">
                <p>Groupe S3B</p> 
                <p>Biancalana Théo</p> 
                <p>Guezennec Lucas</p> 
                <p>Plaid Justin</p> 
                <p>Muller Geoffroy</p>
                <p>Liens du dépôt GIT :<a href="https://bitbucket.org/muller624u/projet_mywishlist_/src/master/" target="_blank">https://bitbucket.org/muller624u/projet_mywishlist_/src/master/</a> </p>
            </div>
        </div>

    </footer> 
</body></html>
END;
        echo $html;
    }




}