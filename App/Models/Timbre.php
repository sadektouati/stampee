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
     * insert or update a stamp
     *
     * @return array
     */
    public static function upsert($columns)
    {
        $db = static::getDB();
        $stmt = $db->prepare('insert into timbre(id, nom, tirage, largeur, longueur, certifie, id_enchere, id_etat, id_pays, id_couleur, est_principal, date_de_creation) 
        select ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? from enchere where id=? and id_utilisateur = ?
        on duplicate key update nom = ?, tirage = ?, largeur = ?, longueur = ?, certifie = ?, id_enchere = ?, id_etat = ?, id_pays = ?, id_couleur = ?, est_principal = ?, date_de_creation = ?');
        $stmt->execute($columns);
        return $db->lastInsertId();
    }

    /**
     * insert the image and update main image if necessary
     *
     * @return int
     */
    public static function insertImage($columns)
    {
        if($columns[1]){
            $db = static::getDB();
            $stmt = $db->prepare('update image set est_principale = false where id_timbre = ?');
            $stmt->execute([$columns[0]]);    
        }

        $db = static::getDB();
        $stmt = $db->prepare('insert into image(id_timbre, est_principale, fichier) values (?, ?, ?)');
        $stmt->execute($columns);
        return $db->lastInsertId();

    }

    /**
     * remove an image
     *
     * @return string
     */
    public static function deleteImage($columns)
    {
        $db = static::getDB();
        $stmt = $db->prepare('select id_timbre, est_principale, fichier from image where id= ?');
        $stmt->execute($columns);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result['est_principale']??false){
            $stmt = $db->prepare('update image set est_principale = true where id=(select max(id) from image where id_timbre= ?)');
            $stmt->execute([$result['id_timbre']]);
        }

        $stmt = $db->prepare('delete from image where id= ?');
        $stmt->execute($columns);

        return $result['fichier']??false;

    }

    /**
     * Get a stamp as an associative array
     *
     * @return array
     */
    public static function getOne($columns)
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT timbre.id, nom, tirage, largeur, longueur, certifie, id_enchere, id_etat, id_pays, id_couleur, est_principal, date_de_creation FROM enchere join timbre on enchere.id=id_enchere where id_utilisateur = ? and timbre.id = ? ");
        $stmt->execute($columns);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * Get if stamp can be main
     *
     * @return boolean
     */
    public static function getPasPossibleEstPrincipal($columns)
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT true as peut_pas FROM enchere join timbre on enchere.id=id_enchere where id_utilisateur = ? and enchere.id = ? and est_principal");
        $stmt->execute($columns);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['peut_pas']??false;
    }

    /**
     * Delete a stamp
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
     * Get countries as an associative array
     *
     * @return array
     */
    public static function listePays()
    {
        $db = static::getDB();
        $stmt = $db->query("SELECT id, nom from pays order by nom");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get colors as an associative array
     *
     * @return array
     */
    public static function listeCouleurs()
    {
        $db = static::getDB();
        $stmt = $db->query("SELECT id, nom from couleur order by nom");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get stamps conditions as an associative array
     *
     * @return array
     */
    public static function listeEtats()
    {
        $db = static::getDB();
        $stmt = $db->query("SELECT id, nom from etat order by nom");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
