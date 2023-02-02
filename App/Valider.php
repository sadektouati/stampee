<?php
namespace App;

trait Valider {
    
    private $original = [];
    private $error = [];
    private $required = [];
    private $donnees = [];
    
    private function validerChampsNonVide($array = ['nom', 'courriel', 'mot_de_passe']){
        foreach ($array as $key) {
            if(isset($this->required[$key])){continue;}
            
            if(empty($_REQUEST[$key])){
                $this->required[$key] = 'le ' . str_replace('_', ' ', $key) .' est requis svp';
            }
        }
    }

    private function validerNom(){
        if (empty($this->required['nom']) and !preg_match("/^[\D ]{3,}$/",$_REQUEST['nom'])) {
            $this->error['nom'] = "Seuls des lettres et des espace svp (trois ou plus)";
        }
        if (empty($this->required['nom']) and mb_strlen($_REQUEST['nom'])>50) {
            $this->error['nom'] = "Maximum 50 lettres est espaces svp";
        }
    }

    private function validerMotDePasse(){
        if ( empty($this->required['mot_de_passe']) ){
            if(mb_strlen($_REQUEST['mot_de_passe']) < 8 ) {
                $this->error['mot_de_passe'] = "Mot de passe invalide";
            }elseif(empty($this->required['confirmation']) and $_REQUEST['mot_de_passe'] != $_REQUEST['confirmation']){
                $this->error['confirmation'] = "Mot de passes différents";
            }
        }
    }

    private function validerCouriel(){
        if (empty($this->required['courriel']) and !filter_var($_REQUEST['courriel'], FILTER_VALIDATE_EMAIL)) {
            $this->error['courriel'] = "Couriel invalide";
        }
    }

    private function chargerDonneesApplication($array){
        $this->donnees['connecte'] = empty($_SESSION['id']) == false;
        foreach ($array as $key => $value) {
            $this->donnees[$key] = $value;
        }
    }

    private function chargerDonneesUtilisateur($session = false){
        if($session and empty($_REQUEST)){
            foreach ($_SESSION as $key => $value) {
                $this->original[$key] = $value;
            }
        }else{
            foreach ($_REQUEST as $key => $value) {
                $this->original[$key] = $value;
            }
        }
    }


    private function validerPrix($array){
        foreach ($array as $key) {
            if(isset($this->required[$key])){continue;}

            if(is_numeric($_REQUEST[$key]) == false or $_REQUEST[$key] < 0 or $_REQUEST[$key] > 10000000){
                $this->error[$key] = 'Le prix doit être numeric et entre 0 et 10000000';
            }
        }
    }
    
    private function chargerSessionUtilisateur($id, $nom, $courriel){
        $_SESSION['id'] = $id;
        $_SESSION['nom'] = $nom;
        $_SESSION['courriel'] = $courriel;
        header('location: /profile/index');
        exit;
    }

    private function validerDate($array){
        foreach ($array as $key) {
            if(isset($this->required[$key])){continue;}

            $dateArray = explode('-', $_REQUEST[$key]??'');
            if(count($dateArray) != 3){
                $this->error[$key] = 'le format de la date est uncorrect.';
            }elseif(checkdate((int)$dateArray[1], (int)$dateArray[2], (int)$dateArray[0]) == false){
                $this->error[$key] = 'la date ' . $key . ' n est pas une date correcte.';
            }
        }
    }

    private function validerIntervalDateFuture($array){
        if(isset($this->required[$array[0]], $this->required[$array[0]]) == false){
            return false;
        }
        
        $debut = new Datetime($_POST[$array[0]]);
        $fin = new Datetime($_POST[$array[1]]);
        $aujourdHui = new Datetime();
        if($aujourdHui >= $fin or $aujourdHui >= $debut){
            $error[$array[0]] = 'Interval erroné, ' . $array[0] . ' et ' . $array[1] . ' doit être dans le future demain ou plutard ';
        }elseif($fin > $aujourdHui->modify('+6 months') or $debut > $aujourdHui->modify('+6 months')){
            $error[$array[0]] = 'Interval erroné, ' . $array[0] . ' et ' . $array[1] . ' doit être dans le future PROCHE. moins de six mois';
        }elseif($fin <= $debut){
            $error[$array[0]] = 'Interval erroné, ' . $array[0] . ' doit être inférieure à ' . $array[1];
        }
    }

    private function validerText($array){
        foreach ($array as $key) {
            if(isset($this->required[$key])){continue;}

            if(mb_strlen($_REQUEST[$key]??'') < 10){
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

            if(is_numeric($_REQUEST[$key]??'') == false){
                $this->error[$key] = 'Doit être numérique, selectionner parmi la liste';
            }elseif($_REQUEST[$key] < 0){
                $this->error[$key] = 'Doit être égale a zéro ou plus...';
            }
        }
    }

    private function doitSauthentifier()
    {
        if(empty($_SESSION['id'])){
            header('location: /profile/connecter');
            exit;
        }
    }

    private function estAuthentifie()
    {
        if(empty($_SESSION['id']) == false){
            header('location: /profile/index');
            exit;
        }
    }

}