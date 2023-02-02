<?php

namespace App\Controllers;

use \Core\View;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Catalogue extends \Core\Controller
{
    use \App\Valider;

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {

        if(empty($_GET['type']) or empty($_GET['recherche'])){
            $encheres = \App\Models\Catalogue::getAll();
            $this->chargerDonneesApplication(['titre' => 'Catalogue']);
        }else{
            $encheres = \App\Models\Catalogue::getAll([$_GET['type'], $_GET['recherche'], $_GET['encours']??null, $_GET['pays']??null]);
            $this->chargerDonneesApplication(['titre' => $_GET['recherche']]);
        }
        $this->chargerDonneesApplication(['encours' => $_GET['encours']??null]);
        $this->chargerDonneesApplication(['pays' => $_GET['pays']??null]);

        $listePays = \App\Models\Timbre::listePays();
        $listeEtats = \App\Models\Timbre::listeEtats();
        $listeCouleurs = \App\Models\Timbre::listeCouleurs();

        View::renderTemplate('Catalogue/index.html', ['donnees' => $this->donnees, 'encheres' => $encheres, 'error' => $this->error, 'required' => $this->required, 'original' => $this->original, 'listepays' => $listePays, 'listeetats' => $listeEtats, 'listecouleurs' => $listeCouleurs]);
        
    }
}
