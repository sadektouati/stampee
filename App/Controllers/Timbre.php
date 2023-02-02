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

    use \App\Valider;

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
        $this->doitSauthentifier();

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
        $this->doitSauthentifier();

        if(empty($this->route_params['id'])){
            $_POST['id_enchere'] = $_GET['id_enchere'];
            $this->chargerDonneesUtilisateur();
        }else{
            $this->original = \App\Models\Timbre::getOne([$_SESSION['id'], $this->route_params['id']]);
        }

        $this->original['pas_possible_est_principal'] = \App\Models\Timbre::getPasPossibleEstPrincipal([$_SESSION['id'], $_GET['id_enchere']]);


        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            if(empty($_POST['id']) and (empty($_FILES['image_principale']) or $_FILES['image_principale']['size'] == 0 or $_FILES['image_principale']['error'] !== 0 )){
                $this->required['image_principale'] = 'image principale requi';
            }

            $donneesLive = true;
            $this->validerChampsNonVide(['id_enchere', 'nom', 'tirage', 'largeur', 'longueur', 'id_etat', 'id_couleur', 'id_pays', 'date_de_creation' ]);
            $this->validerText(['nom']);
            $this->validerIntPositif(['id_etat', 'id_pays', 'largeur', 'longueur', 'tirage', 'id_enchere', 'date_de_creation']);
            $this->validerDbInt(['id_etat', 'id_pays']);
            if(empty($this->required) and empty($this->error)){

                    $id = \App\Models\Timbre::upsert([(empty($_POST['id']) ? null : $_POST['id']), $_POST['nom'], $_POST['tirage'], $_POST['largeur'], $_POST['longueur'], $_POST['certifie']??0, $_POST['id_enchere'], $_POST['id_etat'], $_POST['id_pays'], $_POST['id_couleur'], $_POST['est_principal']??0, $_POST['date_de_creation'], $_POST['id_enchere'], $_SESSION['id'], $_POST['nom'], $_POST['tirage'], $_POST['largeur'], $_POST['longueur'], $_POST['certifie']??0, $_POST['id_enchere'], $_POST['id_etat'], $_POST['id_pays'], $_POST['id_couleur'], $_POST['est_principal']??0, $_POST['date_de_creation']]);
                
                    if(empty($_FILES['image_principale']) == false){
                        $this->createImage((empty($_POST['id']) ? $id : $_POST['id']), $_FILES['image_principale']['tmp_name'], $_FILES['image_principale']['name'], 1);
                    }
                    if(empty($_FILES['images']) == false){
                        $countfiles = count($_FILES['images']['name']);
                        for($i=0;$i<$countfiles;$i++){
                            $this->createImage((empty($_POST['id']) ? $id : $_POST['id']), $_FILES['images']['tmp_name'][$i], $_FILES['images']['name'][$i]);
                        }    
                    }

                    header('location: /enchere/details/' . $_POST['id_enchere']);
                    exit;
            }
       
        }

        if(empty($this->route_params['id'])){
            $this->chargerDonneesApplication(['titre' => 'noveau timbre']);
        }

        $listePays = \App\Models\Timbre::listePays();
        $listeEtats = \App\Models\Timbre::listeEtats();
        $listeCouleurs = \App\Models\Timbre::listeCouleurs();
        View::renderTemplate('Timbre/form.html', ['donnees' => $this->donnees, 'error' => $this->error, 'required' => $this->required, 'original' => $this->original, 'listepays' => $listePays, 'listeetats' => $listeEtats, 'listecouleurs' => $listeCouleurs, 'section' => $_GET['section']??'']);
    }




    private function createImage($id, $fichierTempSurDisk, $fichierOriginal, $estPrincipale = 0){

        if (is_uploaded_file($fichierTempSurDisk)) {
            $extension = strtolower(pathinfo(basename($fichierOriginal),PATHINFO_EXTENSION));
            $imageFileName = '/images/' . random_int(1000000000, 10000000000) . '.' . $extension;
            move_uploaded_file($fichierTempSurDisk, '../public' . $imageFileName);
            $image = \App\Models\Timbre::insertImage([$id, $estPrincipale, $imageFileName]);
        }else{
            return false;
        }

    }


    /**
     * delete image
     *
     * @return void
     */
    public function supprimerImageAction()
    {
        $this->doitSauthentifier();
        
        if(empty($this->route_params['id'])){
            header('location: /');
            exit;
        }

        $this->validerChampsNonVide(['id_enchere']);
        $this->validerIntPositif(['id_enchere']);
        $this->validerDbInt(['id_enchere']);

        if(empty($this->required) and empty($this->error)){

            $fichier = \App\Models\Timbre::deleteImage([$this->route_params['id']]);
            if($fichier == false){
                header('location: /enchere/details/' . $_GET['id_enchere'] . '?erreur=on peut pas supprimer...');
                exit;
            }

            unlink('../public' . $fichier);
            header('location: /enchere/details/' . $_GET['id_enchere']);
        }
    }

}
