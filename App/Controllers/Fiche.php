<?php

namespace App\Controllers;

use \Core\View;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Fiche extends \Core\Controller
{
    use \App\Valider;

    /**
     * Show an auction page
     *
     * @return void
     */
    public function indexAction()
    {
        $timbres = $images = $encheresPertinents = $mises = [];
        $idEnchere = $this->route_params['id']??null;
        $idUtilisateur = ($_SESSION['id']??-1);
        $enchere = \App\Models\Fiche::getAuction([$idUtilisateur, $idUtilisateur, $idUtilisateur, $idEnchere]);
        if($enchere != false){
            $timbres = \App\Models\Fiche::getStamps([$idEnchere]);
            $images = \App\Models\Fiche::getImages([$idEnchere]);
            $mises = \App\Models\Fiche::getBids([$idEnchere]);
            $this->chargerDonneesApplication(['titre' => $enchere['titre']]);
        }
        $encheresPertinents = \App\Models\Fiche::getSimilarAuctions([$idUtilisateur, $idEnchere]);
        View::renderTemplate('Fiche/index.html', ['donnees' => $this->donnees, 'original' => $this->original, 'error' => $this->error, 'required' => $this->required, 'enchere' => $enchere, 'timbres' => $timbres, 'images' => $images, 'encheres' => $encheresPertinents, 'mises' => $mises]);
    }


    /**
     * Save a bid
     *
     * @return void
     */
    public function placerMiseAction()
    {
        $this->doitSauthentifier();
        $this->validerChampsNonVide(['mise']);
        $this->validerPrix(['mise']);
        $idEnchere = $this->route_params['id']??null;
        $this->chargerDonneesUtilisateur();
        
        if(empty($this->required) and empty($this->error)){
            $placerMise = \App\Models\Fiche::upsertBid([$_SESSION['id'], $idEnchere, $_POST['mise'], $_POST['id']??null, $_SESSION['id'], $idEnchere, $_POST['mise']]);
            if($placerMise != false){
                header('location: /fiche/index/' . $idEnchere);
                exit;
            }
        }

    $this->indexAction();
    }

}
