<?php

namespace App\Controllers;

use \Core\View;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Enchere extends \Core\Controller
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

        $encheres = \App\Models\Enchere::getAll();
        $this->chargerDonneesApplication(['titre' => 'Mes encheres']);

        View::renderTemplate('Enchere/index.html', ['donnees' => $this->donnees, 'encheres' => $encheres]);

    }

        /**
     * Show the index page
     *
     * @return void
     */
    public function detailsAction($modifier = false, $donneesLive = false)
    {
        if(empty($_SESSION['id'])){
            header('location: /profile/connecter');
            exit;
        }

        $timbres = [];
        if($donneesLive){
            $this->chargerDonneesUtilisateur();
        }else{
            $idEnchere = $this->route_params['id']??null ;
            $this->original = \App\Models\Enchere::getOne($idEnchere);
            $timbres = \App\Models\Enchere::getAllStamps([$_SESSION['id'], $idEnchere]);
        }
        $this->chargerDonneesApplication(['titre' => $this->original['titre']??'nouvelle enchere', 'formclass' => $modifier ? '' : 'pas-form']);

        View::renderTemplate('Enchere/form.html', ['donnees' => $this->donnees, 'error' => $this->error, 'required' => $this->required, 'original' => $this->original, 'timbres' => $timbres]);

    }

    /**
     * modifier une enchere
     *
     * @return void
     */
    public function nouveauAction()
    {
        $this->modifierAction();
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

        $modifier = true;
        $donneesLive = false;

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $donneesLive = true;
            $this->validerChampsNonVide(['debut', 'fin', 'prix_plancher']);
            $this->validerDate(['debut', 'fin']);
            $this->validerPrix(['prix_plancher']);
            $this->validerText(['titre', 'commentaire']);

            if(empty($this->required) and empty($this->error)){

                    $id = \App\Models\Enchere::upsert([(empty($_POST['id'])?null:$_POST['id']), $_POST['titre'], $_POST['debut'], $_POST['fin'], $_POST['prix_plancher'], $_POST['est_enligne']??0, $_POST['commentaire'], $_SESSION['id']]);

                if($id == false){
                    $this->error['creerenchere'] = "Il y a un probleme sérieux";
                }else{
                    header('location: /enchere/details/' . (empty($_POST['id'])?$id:$_POST['id']));
                    exit;
                }
            }
       
        }

        if(empty($this->route_params['id'])){
            $this->chargerDonneesApplication(['titre' => 'novelle enchere']);
        }

        $this->detailsAction($modifier, $donneesLive);
    }

    private function validerChampsNonVide($array = []){
        foreach ($array as $value) {
            if(empty($_POST[$value])){
                $this->required[$value] = 'le ' . str_replace('_', ' ', $value) .' est requis svp';
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

            $dateArray = explode('-', $_POST[$key]);
            if(count($dateArray) != 3){
                $this->error[$key] = 'le format de la date est uncorrect.';
            }elseif(checkdate((int)$dateArray[1], (int)$dateArray[2], (int)$dateArray[0]) == false){
                $this->error[$key] = 'la date ' . $key . ' n est pas une date correcte.';
            }
        }
    }

    private function validerPrix($array){
        foreach ($array as $key) {
            if(isset($this->required[$key])){continue;}

            if(is_numeric($_POST[$key]) == false or $_POST[$key] < 0 or $_POST[$key] > 10000000){
                $this->error[$key] = 'Le prix doit être numeric et entre 0 et 10000000';
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

}
