<?php

namespace App\Models;

use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class Profile extends \Core\Model
{

    /**
     * Get all the users as an associative array
     *
     * @return array
     */
    public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT id, name FROM users');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * insert the member
     *
     * @return int
     */
    public static function insert($columns)
    {
        $db = static::getDB();
        $stmt = $db->prepare('insert IGNORE into utilisateur(nom, mot_de_passe, courriel) values (?, ?, ?)');
        $result = $stmt->execute($columns);
        return $db->lastInsertId();

    }

        /**
     * insert the member
     *
     * @return int
     */
    public static function update($columns)
    {
        $db = static::getDB();
        $stmt = $db->prepare('update utilisateur set nom= ?, courriel = ? where id = ?');
        return $stmt->execute($columns);

    }

    /**
     * Get user as an associative array
     *
     * @return array
     */
    public static function getOne($courriel)
    {
        $db = static::getDB();
        $stmt = $db->prepare('SELECT id, nom, mot_de_passe FROM utilisateur where courriel = ?');
        $stmt->execute($courriel);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

        /**
     * Get user as an associative array
     *
     * @return array
     */
    public static function getProfile()
    {
        $db = static::getDB();
        $stmt = $db->prepare('SELECT id, nom, courriel FROM utilisateur where id = ?');
        $stmt->execute([$_SESSION['id']]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


}
