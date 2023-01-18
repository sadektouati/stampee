<?php

namespace App\Models;

use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class Timbre extends \Core\Model
{

    /**
     * insert the stamp
     *
     * @return int
     */
    public static function upsert($columns)
    {
        $db = static::getDB();
        $stmt = $db->prepare('REPLACE into timbre(id, nom, tirage, largeur, longueur, certifie, id_enchere, id_etat, id_pays, est_principal, date_de_creation) 
        select ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? from enchere where id=? and id_utilisateur = ?');
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
        $stmt = $db->prepare("SELECT timbre.id, nom, tirage, largeur, longueur, certifie, id_enchere, id_etat, id_pays, est_principal, date_de_creation FROM enchere join timbre on enchere.id=id_enchere where id_utilisateur = ? and timbre.id = ? ");
        $stmt->execute([$_SESSION['id'], $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get auction as an associative array
     *
     * @return array
     */
    public static function deleteOne($id)
    {
        $db = static::getDB();
        $stmt = $db->prepare("delete from timbre where id = (select timbre.id from enchere join timbre on enchere.id=id_enchere where id_utilisateur = ? and timbre.id = ? )");
        $stmt->execute([$_SESSION['id'], $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * Get auction as an associative array
     *
     * @return array
     */
    public static function listePays()
    {
        $db = static::getDB();
        $stmt = $db->query("SELECT id, nom from pays");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * Get auction as an associative array
     *
     * @return array
     */
    public static function listeEtat()
    {
        $db = static::getDB();
        $stmt = $db->query("SELECT id, nom from etat");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
