<?php

namespace App\Models;

use PDO;

/**
 * Example user Fiche
 *
 * PHP version 7.0
 */
class Fiche extends \Core\Model
{

    /**
     * Get auction's data as an associative array
     *
     * @return array
     */
    public static function getAuction($tableau)
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT enchere.id, titre, commentaire, debut, fin, prix_plancher, offre_actuel, offre_actuel_membre, quantite_mise, a_coup_de_coeur_lord, est_enligne, delais_depasse, pas_commence, (select valeur from mise where id_enchere=enchere.id and id_utilisateur=?) mise, id_utilisateur!=? peut_rajouter, case when TIMESTAMPDIFF(HOUR, now(), fin) <= 24 then TIMESTAMPDIFF(HOUR, now(), fin) else null end heures_restantes, (select true from favoris where id_enchere=enchere.id and id_utilisateur = ?) est_favoris, nom titre_timbre_principal, fichier image_principale FROM enchere join timbre on enchere.id=id_enchere left join image on (timbre.id=id_timbre  and image.est_principale) where  valide and enchere.id = ? and timbre.est_principal and est_enligne");
        $stmt->execute($tableau);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get stamps as associative arraies
     *
     * @return array
     */
    public static function getStamps($columns)
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT timbre.id, nom, tirage, largeur, longueur, certifie, id_enchere, (select nom from etat where etat.id=id_etat) etat, (select nom from pays where pays.id=id_pays) pays, (select nom from couleur where couleur.id=id_couleur) couleur, est_principal, date_de_creation FROM enchere join timbre on enchere.id=id_enchere where  valide and  id_enchere = ? order by est_principal");
        $stmt->execute($columns);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get stamps as associative arraies
     *
     * @return array
     */
    public static function getBids($columns)
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT date, valeur, nom FROM mise join utilisateur on id_utilisateur=utilisateur.id where id_enchere = ? order by date desc");
        $stmt->execute($columns);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get auction as an associative array
     *
     * @return array
     */
    public static function getImages($columns)
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT image.fichier, est_principal and est_principale est_principale, timbre.nom nom_timbre FROM enchere join timbre on enchere.id=id_enchere join image on (timbre.id=id_timbre) where enchere.id = ? and valide order by est_principale desc, est_principal desc");
        $stmt->execute($columns);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get some auctions as an associative array
     *
     * @return array
     */
    public static function getSimilarAuctions($columns)
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT enchere.id, titre, commentaire, debut, fin, prix_plancher, offre_actuel, offre_actuel_membre, quantite_mise, a_coup_de_coeur_lord, est_enligne, delais_depasse, pas_commence, fichier FROM enchere join timbre on enchere.id=id_enchere  left join image on (id_timbre=timbre.id and est_principale) where valide and est_principal and id_utilisateur != ? and enchere.id != ? order by enchere.date_ajout desc limit 5");
        $stmt->execute($columns);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Upsert bid
     *
     * @return array
     */
    public static function upsertBid($columns)
    {
        $db = static::getDB();
        $stmt = $db->prepare("insert into mise(id_utilisateur, id_enchere, valeur, id) select ?, ?, ?, ? from enchere where valide and enchere.id_utilisateur!=? and enchere.id=? on duplicate key update valeur=?");
        $stmt->execute($columns);
        $mise =  $db->lastInsertId();

        $stmt = $db->prepare("update enchere set quantite_mise = (select count(*) from mise where id_enchere=?), offre_actuel = (select max(valeur) from mise where id_enchere=?), offre_actuel_membre = (select nom from mise join utilisateur on utilisateur.id=id_utilisateur where id_enchere=? order by valeur desc limit 1) WHERE enchere.id=?");
        $stmt->execute([$columns[5], $columns[5], $columns[5], $columns[5]]);
        
        return $mise??$columns[3];
    }

}
