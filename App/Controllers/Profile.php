<?php

namespace App\Controllers;

use \Core\View;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Profile extends \Core\Controller
{

    private $original = [];
    private $error = [];
    private $required = [];
    private $donnees = [];
    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        if(empty($_SESSION['id'])){
            header('location: /profile/connecter');
            exit;
        }
        $utilisateur = \App\Models\Profile::getProfile();
        $this->chargerDonneesApplication(['titre' => 'mon profile', 'formclass' => 'pas-form']);

        View::renderTemplate('Profile/index.html', ['donnees' => $this->donnees, 'original' => $utilisateur]);

    }

    /**
     * Show the index page
     *
     * @return void
     */
    public function modifierAction()
    {
        if(empty($_SESSION['id'])){
            header('location: /profile/connecter');
            exit;
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
           
            $this->validerChampsNonVide(['nom', 'courriel']);
            $this->validerNom();
            $this->validerCouriel();
            

            if(empty($this->required) and empty($this->error)){

                $mettreAjour = \App\Models\Profile::update([$_POST['nom'], $_POST['courriel'], $_SESSION['id']]);
                if($mettreAjour == false){
                    $this->error['courriel'] = "courriel deja utilisé";
                }else{
                    
                    $this->chargerSessionUtilisateur($_SESSION['id'], $_POST['nom'], $_POST['courriel']);
                                       
                }
            }
       
        }

        $this->chargerDonneesApplication(['titre' => 'modifier mon profile', 'formclass' => 'update-form']);
        $this->chargerDonneesUtilisateur(true);
        
        View::renderTemplate('Profile/index.html', ['donnees' => $this->donnees, 'original' => $this->original, 'required' => $this->required, 'error' => $this->error]);
    }

    /**
     * Show the create profile page
     *
     * @return void
     */
    public function nouveauAction()
    {
        if(empty($_SESSION['id']) == false){
            header('location: /profile/index');
            exit;
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
           
            $this->validerChampsNonVide();
            $this->validerNom();
            $this->validerCouriel();
            $this->validerMotDePasse();

            if(empty($this->required) and empty($this->error)){
                    $motDePasseChifré = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
                    $identifiant = \App\Models\Profile::insert([$_POST['nom'], $motDePasseChifré, $_POST['courriel']]);
                    if($identifiant == false){
                        $this->error['courriel'] = "courriel deja utilisé";
                    }else{

                        $this->chargerSessionUtilisateur($identifiant, $_POST['nom'], $_POST['courriel']);
                    
                    }
                }

                $this->chargerDonneesUtilisateur(true);
        }

        $this->chargerDonneesApplication(['titre' => 'noveau profile']);

        View::renderTemplate('Profile/index.html', ['donnees' => $this->donnees, 'original' => $this->original, 'required' => $this->required, 'error' => $this->error]);
}


    /**
     * Show the create profile page
     *
     * @return void
     */
    public function connecterAction()
    {
        if(empty($_SESSION['id']) == false){
            header('location: /profile/index');
            exit;
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
           
            $this->validerChampsNonVide(['courriel', 'mot_de_passe']);
            $this->validerCouriel();

            
            if(empty($this->required) and empty($this->error)){
                
                $utilisateur = \App\Models\Profile::getOne([$_POST['courriel']]);
                if($utilisateur == false or password_verify($_POST['mot_de_passe'], $utilisateur['mot_de_passe']) == false){
                    $this->error['courriel'] = "courriel ou mot de passe érroné";
                }else{
                    
                    $this->chargerSessionUtilisateur($utilisateur['id'], $utilisateur['nom'], $_POST['courriel']);

                }
            }

            $this->chargerDonneesUtilisateur(true);
        }

        View::renderTemplate('Profile/connecter.html', ['original' => $this->original, 'required' => $this->required, 'error' => $this->error]);
    }


    /**
     * Show the create profile page
     *
     * @return void
     */
    public function deconnecterAction()
    {
            session_destroy();
            header('location: /');
            exit;
    }

    private function validerChampsNonVide($array = ['nom', 'courriel', 'mot_de_passe']){
        foreach ($array as $key) {
            if(isset($this->required[$key])){continue;}

            if(empty($_POST[$key])){
                $this->required[$key] = 'le ' . str_replace('_', ' ', $key) .' est requis svp';
            }
        }
    }

    private function chargerDonneesApplication($array){
        foreach ($array as $key => $value) {
            $this->donnees[$key] = $value;
        }
    }

    private function chargerDonneesUtilisateur($session = false){
        if($session and empty($_POST)){
            foreach ($_SESSION as $key => $value) {
                $this->original[$key] = $value;
            }
        }else{
            foreach ($_POST as $key => $value) {
                $this->original[$key] = $value;
            }
        }
    }

    private function validerNom(){
        if (empty($this->required['nom']) and !preg_match("/^[\D ]{3,}$/",$_POST['nom'])) {
            $this->error['nom'] = "Seuls des lettres et des espace svp (trois ou plus)";
        }
        if (empty($this->required['nom']) and mb_strlen($_POST['nom'])>50) {
            $this->error['nom'] = "Maximum 50 lettres est espaces svp";
        }
    }

    private function validerMotDePasse(){
        if ( empty($this->required['mot_de_passe']) ){
            if(mb_strlen($_POST['mot_de_passe']) < 8 ) {
                $this->error['mot_de_passe'] = "Mot de passe invalide";
            }elseif(empty($this->required['confirmation']) and $_POST['mot_de_passe'] != $_POST['confirmation']){
                $this->error['confirmation'] = "Mot de passes différents";
            }
        }
    }

    private function validerCouriel(){
        if (empty($this->required['courriel']) and !filter_var($_POST['courriel'], FILTER_VALIDATE_EMAIL)) {
            $this->error['courriel'] = "Couriel invalide";
        }
    }


    private function chargerSessionUtilisateur($id, $nom, $courriel){
        $_SESSION['id'] = $id;
        $_SESSION['nom'] = $nom;
        $_SESSION['courriel'] = $courriel;
        header('location: /profile/index');
        exit;
    }
}
