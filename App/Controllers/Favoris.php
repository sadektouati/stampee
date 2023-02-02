<?php

namespace App\Controllers;

use \Core\View;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Favoris extends \Core\Controller
{
    use \App\Valider;

    /**
     * Show all favourites
     *
     * @return void
     */
    public function indexAction()
    {
        $this->doitSauthentifier();
        $favoris = \App\Models\Favoris::getAll();
        $this->chargerDonneesApplication(['titre' => 'Mes favoris']);

        View::renderTemplate('Favoris/index.html', ['donnees' => $this->donnees, 'favoris' => $favoris]);

    }

    /**
     * add a favourite auction
     *
     * @return void
     */
    public function ajouterAction()
    {

        $this->validerIdentifiantEnchere();
        
            $resultat = \App\Models\Favoris::insert([$this->route_params['id'], $_SESSION['id']]);

            $location = '/fiche/index/' . $this->route_params['id'];
            if($resultat == false){
                $location .= "?erreur=Il y a un probleme sérieux"; 
            }
            header('location: ' . $location);
            exit;

    }

    /**
     * delete a favourite auction
     *
     * @return void
     */
    public function supprimerAction()
    {
        $this->doitSauthentifier();
        $this->validerIdentifiantEnchere();
        
            $resultat = \App\Models\Favoris::delete([$this->route_params['id'], $_SESSION['id']]);

            $location = '/fiche/index/' . $this->route_params['id'];
            if($resultat == false){
                header('location: ?erreur=Il y a un probleme sérieux' );
            }
            var_dump($resultat); exit;
            $this->indexAction();
    }

    private function validerIdentifiantEnchere(){
        if(empty($this->route_params['id'])){
            $this->indexAction();
            exit;
        }
    }
}
