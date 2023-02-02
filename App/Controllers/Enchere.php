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
    use \App\Valider;

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        $this->doitSauthentifier();
        $encheres = \App\Models\Enchere::getAll([$_SESSION['id']]);
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
        $this->doitSauthentifier();

        $timbres = [];
        if($donneesLive){
            $this->chargerDonneesUtilisateur();
        }else{
            $idEnchere = $this->route_params['id']??null;
            $this->original = \App\Models\Enchere::getOne($idEnchere);
            $timbres = \App\Models\Enchere::getAllStamps([$_SESSION['id'], $idEnchere]);
            foreach($timbres as $k => $timbre){
                $timbres[$k]['images'] = \App\Models\Enchere::getAllStampImages([$timbre['id']]);
            }
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
        $this->doitSauthentifier();

        $modifier = true;
        $donneesLive = false;

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $donneesLive = true;
            $this->validerChampsNonVide(['debut', 'fin', 'prix_plancher']);
            $this->validerDate(['debut', 'fin']);
            $this->validerIntervalDateFuture(['debut', 'fin']);
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

        $aujourdHui = new \Datetime();
        $this->donnees['today'] = $aujourdHui->format('Y-m-d');
        $this->donnees['maxbeginday'] = $aujourdHui->modify('+6 months')->format('Y-m-d');
        $this->donnees['maxendday'] = $aujourdHui->modify('+12 months')->format('Y-m-d');
        $this->detailsAction($modifier, $donneesLive);
    }

}
