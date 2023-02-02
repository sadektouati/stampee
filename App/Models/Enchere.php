<?php

namespace App\Models;

use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class Enchere extends \Core\Model
{

    /**
     * Get all the users as an associative array
     *
     * @return array
     */
    public static function getAll($columns)
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT enchere.id, titre, commentaire, debut, fin, prix_plancher, offre_actuel, offre_actuel_membre, quantite_mise, a_coup_de_coeur_lord, est_enligne, delais_depasse, pas_commence, (select fichier from image where image.id_timbre = timbre.id and est_principale) fichier FROM enchere left join timbre on (enchere.id=id_enchere and est_principal) where id_utilisateur = ?");
        $stmt->execute($columns);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * insert the auction
     *
     * @return int
     */
    public static function upsert($columns)
    {
        $db = static::getDB();
        //this one have a security issue...
        $stmt = $db->prepare('insert into enchere(id, titre, debut, fin, prix_plancher, est_enligne, commentaire, id_utilisateur) values (?, ?, ?, ?, ?, ?, ?, ?)
        on duplicate key update
        titre = values(titre), debut=values(debut), fin=values(fin), prix_plancher=values(prix_plancher), est_enligne=values(est_enligne), commentaire=values(commentaire)
        ');
        $result = $stmt->execute($columns);
        return $db->lastInsertId();

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
        $stmt = $db->prepare("SELECT timbre.id, nom, tirage, largeur, longueur, certifie, id_enchere, (select nom from etat where etat.id=id_etat) etat, (select nom from pays where pays.id=id_pays) pays, (select nom from couleur where couleur.id=id_couleur) couleur, est_principal, date_de_creation FROM enchere join timbre on enchere.id=id_enchere where id_utilisateur = ? and id_enchere = ? ");
        $stmt->execute($columns);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Get auction as an associative array
     *
     * @return array
     */
    public static function getAllStampImages($columns)
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT id, fichier, est_principale FROM image where id_timbre = ? order by est_principale desc");
        $stmt->execute($columns);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
