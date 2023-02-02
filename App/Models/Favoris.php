<?php

namespace App\Models;

use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class Favoris extends \Core\Model
{

    /**
     * Get all the users as an associative array
     *
     * @return array
     */
    public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT enchere.id, titre, commentaire, debut, fin, prix_plancher, offre_actuel, offre_actuel_membre, quantite_mise, a_coup_de_coeur_lord, est_enligne, delais_depasse, pas_commence, fichier FROM enchere join favoris on enchere.id=id_enchere join timbre on enchere.id=id_enchere left join image on (id_timbre=timbre.id and est_principale) where favoris.id_utilisateur = ? and est_principal order by date_d_ajout desc");
        $stmt->execute([$_SESSION['id']]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * insert favourite auction
     *
     * @return int
     */
    public static function insert($columns)
    {
        $db = static::getDB();
        try {
            $stmt = $db->prepare('insert into favoris(id_enchere, id_utilisateur) values (?, ?)');
            $result = $stmt->execute($columns);
        } catch (\Throwable $th) {
            $result = false;
        }
        
        return $result;

    }

    /**
     * delete the auction
     *
     * @return int
     */
    public static function delete($columns)
    {
        $db = static::getDB();
        //this one have a security issue...
        $stmt = $db->prepare('delete from favoris where id_utilisateur = ? and id_enchere = ?');
        $result = $stmt->execute($columns);
        return $result;

    }

    /**
     * Get auction as an associative array
     *
     * @return array
     */
    public static function getOne($id)
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT enchere.id, titre, commentaire, debut, fin, prix_plancher, offre_actuel, offre_actuel_membre, quantite_mise, a_coup_de_coeur_lord, est_enligne, delais_depasse, pas_commence FROM enchere left join timbre on enchere.id=id_enchere where id_utilisateur = ? and enchere.id = ?");
        $stmt->execute([$_SESSION['id'], $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get auction as an associative array
     *
     * @return array
     */
    public static function getAllStamps($columns)
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT timbre.id, nom, tirage, largeur, longueur, certifie, id_enchere, (select nom from etat where etat.id=id_etat) etat, (select nom from pays where pays.id=id_pays) pays, est_principal, date_de_creation FROM enchere join timbre on enchere.id=id_enchere where id_utilisateur = ? and id_enchere = ? ");
        $stmt->execute($columns);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}
