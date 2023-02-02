<?php

namespace App\Models;

use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class Catalogue extends \Core\Model
{

    /**
     * Get all the auctions as an associative array
     *
     * @return array
     */
    public static function getAll($columns = [])
    {
        $db = static::getDB();

        $query = "SELECT count(*) over() total,  enchere.id, titre, commentaire, debut, fin, prix_plancher, offre_actuel, offre_actuel_membre, quantite_mise, a_coup_de_coeur_lord, est_enligne, delais_depasse, pas_commence, fichier FROM enchere join timbre on enchere.id=id_enchere left join image on (id_timbre=timbre.id and est_principale)  where valide = " . ($columns[2]??'valide') . " and est_principal " . (empty($columns[3]) ? "" : " and id_pays = " . $columns[3]);
        $orderBy = " order by enchere.date_ajout desc limit 30";
        if(empty($columns)){
            $stmt = $db->query($query . $orderBy);
        }else if($columns[0] == 'timbre'){
            $stmt = $db->prepare($query . " and timbre.nom like ? " . $orderBy);
            $stmt->execute(['%' . $columns[1] . '%']);
        }else{
            $stmt = $db->prepare($query . " and (enchere.titre like ? or enchere.commentaire like ?) " . $orderBy);
            $stmt->execute(['%' . $columns[1] . '%', '%' . $columns[1] . '%']);
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
