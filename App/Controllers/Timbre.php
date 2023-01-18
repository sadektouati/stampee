<?php

namespace App\Controllers;

use \Core\View;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Timbre extends \Core\Controller
{

    private $original = [];
    private $error = [];
    private $required = [];
    private $donnees = [];

    public function nouveauAction()
    {
        $this->modifierAction();
    }

        /**
     * modifier une enchere
     *
     * @return void
     */
    public function supprimerAction()
    {
        if(empty($_SESSION['id'])){
            header('location: /profile/connecter');
            exit;
        }

        $supprimerTimbre = \App\Models\Timbre::deleteOne($this->route_params['id']);

        if(empty($_GET["id_enchere"])){
            header('location: /enchere/index');
        }else{
            header('location: /enchere/details/' . $_GET["id_enchere"]);
        }
        exit;
    
    }

    /**
     * modifier une enchere
     *
     * @return void
     */
    public function modifierAction()
    {
        if(empty($_SESSION['id'])){
            header('location: /profile/connecter');
            exit;
        }
        if(empty($this->route_params['id'])){
            $_POST['id_enchere'] = $_GET['id_enchere'];
            $this->chargerDonneesUtilisateur();
        }else{
            $this->original = \App\Models\Timbre::getOne($this->route_params['id']);
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $donneesLive = true;
            $this->validerChampsNonVide(['id_enchere', 'nom', 'tirage', 'largeur', 'longueur', 'id_etat', 'id_pays', 'date_de_creation' ]);
            $this->validerDate(['date_de_creation']);
            $this->validerText(['nom']);
            $this->validerIntPositif(['id_etat', 'id_pays', 'largeur', 'longueur', 'tirage', 'id_enchere']);
            $this->validerDbInt(['id_etat', 'id_pays']);
            if(empty($this->required) and empty($this->error)){

                    $id = \App\Models\Timbre::upsert([(empty($_POST['id']) ? null : $_POST['id']), $_POST['nom'], $_POST['tirage'], $_POST['largeur'], $_POST['longueur'], $_POST['certifie']??0, $_POST['id_enchere'], $_POST['id_etat'], $_POST['id_pays'], $_POST['est_principal']??0, $_POST['date_de_creation'], $_POST['id_enchere'], $_SESSION['id']]);

                if($id == false){
                    $this->error['creer'] = "Il y a un probleme sérieux";
                }else{
                    header('location: /enchere/details/' . $_POST['id_enchere']);
                    exit;
                }
            }
       
        }

        if(empty($this->route_params['id'])){
            $this->chargerDonneesApplication(['titre' => 'noveau timbre']);
        }

        $listePays = \App\Models\Timbre::listePays();
        $listeEtats = \App\Models\Timbre::listeEtat();
        View::renderTemplate('Timbre/form.html', ['donnees' => $this->donnees, 'error' => $this->error, 'required' => $this->required, 'original' => $this->original, 'listepays' => $listePays, 'listeetats' => $listeEtats]);
    }

    private function validerChampsNonVide($array = []){
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

    private function validerDate($array){
        foreach ($array as $key) {
            if(isset($this->required[$key])){continue;}

            $dateArray = explode('-', $_POST[$key]??'');
            if(count($dateArray) != 3){
                $this->error[$key] = 'le format de la date est uncorrect.';
            }elseif(checkdate((int)$dateArray[1], (int)$dateArray[2], (int)$dateArray[0]) == false){
                $this->error[$key] = 'la date ' . $key . ' n est pas une date correcte.';
            }
        }
    }

    private function validerText($array){
        foreach ($array as $key) {
            if(isset($this->required[$key])){continue;}

            if(mb_strlen($_POST[$key]??'') < 10){
                $this->error[$key] = 'Au moins dix characteres';
            }
        }
    }

    private function validerDbInt($array){
        foreach ($array as $key) {
            if(isset($this->required[$key])){continue;}

            //Should implement database checks...
        }
    }

    private function validerIntPositif($array){
        foreach ($array as $key) {
            if(isset($this->required[$key])){continue;}

            if(is_numeric($_POST[$key]??'') == false){
                $this->error[$key] = 'Doit être numérique, selectionner parmi la liste';
            }elseif($_POST[$key] < 0){
                $this->error[$key] = 'Doit être égale a zéro ou plus...';
            }
        }
    }

}
