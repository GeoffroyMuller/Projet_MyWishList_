<?php
/**
 * Created by PhpStorm.
 * User: Lucas
 * Date: 26/12/2018
 * Time: 11:08
 */

namespace mywishlist\controlleurs;


class ControleurInternaute
{
    const UTILISATEUR_INSCRIT = 1;
    /**
     * Méthode permettant d'inscire un utilisateur
     * @param $uName
     * @param $uPass
     */
    public function inscrire($uName, $uPass){
        $hash = password_hash($uPass, PASSWORD_DEFAULT);
        try{
            if($this->verifierNomUtilisateur($uName)){
                $utilisateur = new \mywishlist\models\Utilisateur();
                $utilisateur->uPass = $hash;
                $utilisateur->uName = $uName;
                $utilisateur->grade = self::UTILISATEUR_INSCRIT;
                $utilisateur->save();

                //Si l'utilisateur à crée des listes en tant qu'invité alors on les ajoutes à son compte
                $listesInvite = \mywishlist\models\Liste::where("user_id",'=',-1)->get();

                foreach ($listesInvite as $liste){
                    $titreDeLaListe = $liste->titre;
                    if(isset($_COOKIE[$titreDeLaListe])){

                        $listeAAjouter = \mywishlist\models\Liste::where("titre",'=',$liste->titre)->first();
                        $listeAAjouter->user_id = $utilisateur->idUser;
                        $listeAAjouter->save();
                    }

                }
            }else{
                throw new \mywishlist\Exception\AuthException("Le nom d'utilisateur est déja utilisé");
            }
        }catch(\mywishlist\Exception\AuthException $e){
            /**
             * Page d'erreur
             */
        }




    }

    /**
     * Méthode permettant de vérifier si un nom d'utilisateur est déja occupé
     * @param $uName
     * @return bool
     */
    private function verifierNomUtilisateur($uName){
        $res=true;
        if(\mywishlist\models\Utilisateur::where('uName','=',$uName)->first()){
            $res=false;
        }
        return $res;
    }

    public function seConnecter($uName, $uPass){
        //Check credential
        try{
            $utilisateur = \mywishlist\models\Utilisateur::where('uName','=',$uName)->first();
            if(is_null($utilisateur)){
                throw new \mywishlist\Exception\AuthException("Le login saisi est incorrect");
            }

            if(password_verify($uPass,$utilisateur->uPass)){
                $this->chargerProfil($utilisateur->idUser);
            }else{
                throw new \mywishlist\Exception\AuthException("Le mot de passe saisi est incorrect");
            }
        }catch(\mywishlist\Exception\AuthException $e){
            /**
             * Rediriger vers une page d'erreur
             */
        }

    }

    /**
     * Méthode permettant de charger le profil de l'utilisateur dans une variable session
     * @param $uName
     */
    private function chargerProfil($uId){
        $utilisateur = \mywishlist\models\Utilisateur::where('idUser','=',$uId)->first();
        session_destroy();
        session_start();
        $_SESSION['profile']['username'] = $utilisateur->uName;
        $_SESSION['profile']['userId'] = $utilisateur->idUser;
        $_SESSION['profile']['grade'] = $utilisateur->grade;
        $_SESSION['profile']['client_ip'] = $_SERVER['REMOTE_ADDR'];
        $_SESSION['profile']['auth_level'] = 10000;
    }

    /**
     * Méthode permettant la vérification des droits
     * @param $gradeRequis
     * @return bool
     */
    public function verifierDroit($gradeRequis){
        return $_SESSION['profile']['grade'] >= $gradeRequis;
    }

    /**
     * Méthode permettant de se déconnecter
     */
    public function deconnexion(){
        session_destroy();
        session_start();
    }

    /**
     * Méthode permettant la suppression d'un compte
     */
    public function suppCompte(){
        $utilisateur = \mywishlist\models\Utilisateur::where('idUser','=',$_SESSION['profile']['userId'])->first()->delete();
        $this->deconnexion();
    }

    /**
     * Méthode permettant de déterminer si l'utilisateur connecté est bien le créateur de l'item passé en paramétre
     * @param $idItem
     * @return bool
     */
    public static function testerAppartenanceItem($idItem){
        $element = \mywishlist\models\Item::where('id','=',$idItem)->first();
        $element = $element->liste();

        if(is_null($element)){
            return false;
        }

        $element = $element->first();

        $res=false;
        if($element->user_id === $_SESSION['profile']['userId']){
            $res=true;
        }
        return $res;
    }

}