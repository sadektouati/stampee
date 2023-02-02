<?php

namespace App\Controllers;

use \Core\View;
use \App\Models;
use \App;


/**
 * Home controller
 *
 * PHP version 7.0
 */
class Profile extends \Core\Controller
{
    use \App\Valider;

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        $this->doitSauthentifier();

        $utilisateur = Models\Profile::getProfile();
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
        $this->doitSauthentifier();

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
           
            $this->validerChampsNonVide(['nom', 'courriel']);
            $this->validerNom();
            $this->validerCouriel();
            

            if(empty($this->required) and empty($this->error)){

                $mettreAjour = Models\Profile::update([$_POST['nom'], $_POST['courriel'], $_SESSION['id']]);
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
        $this->estAuthentifie();

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
           
            $this->validerChampsNonVide();
            $this->validerNom();
            $this->validerCouriel();
            $this->validerMotDePasse();

            if(empty($this->required) and empty($this->error)){
                    $motDePasseChifré = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
                    $identifiant = Models\Profile::insert([$_POST['nom'], $motDePasseChifré, $_POST['courriel']]);
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
        $this->estAuthentifie();

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            
            $this->validerChampsNonVide(['courriel', 'mot_de_passe']);
            $this->validerCouriel();

            
            if(empty($this->required) and empty($this->error)){
                
                $utilisateur = Models\Profile::getOne([$_POST['courriel']]);
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
}
