<?php

namespace App\Controllers;

use \Core\View;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Home extends \Core\Controller
{
    use \App\Valider;

    /**
     * load Live auctions
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {

        $encheres = \App\Models\Home::getAllVedetteAuctions();
        View::renderTemplate('Home/index.html', ['donnees' => $this->donnees, 'encheres' => $encheres]);
        
    }

    public function fakeAction()
    {

        \App\Models\Home::fake($_GET['quoi']??null);
        
    }


}
