<?php

namespace App\Core;

use PDO;
use PDOException;

/**
 * Classe Database - Gestionnaire de connexion PDO (patron Singleton).
 *
 * Cette classe cree une seule connexion PDO partagee par toute l'application.
 * Le patron Singleton evite d'ouvrir plusieurs connexions simultanees
 * vers la base de donnees, ce qui ameliore les performances et la stabilite.
 *
 * Methodes PDO utilisees dans le projet :
 *   - new PDO($dsn, $user, $password) : etablit la connexion
 *   - $pdo->prepare($sql)             : prepare une requete parametree (securisee contre les injections SQL)
 *   - $stmt->execute($params)         : execute la requete avec ses parametres
 *   - $stmt->fetch()                  : recupere une seule ligne de resultat
 *   - $stmt->fetchAll()               : recupere toutes les lignes de resultat
 *   - $stmt->fetchColumn()            : recupere la premiere colonne de la premiere ligne
 *   - $stmt->closeCursor()            : libere les ressources du curseur apres lecture
 *   - $pdo->query($sql)               : execute une requete simple sans parametre
 *   - $pdo->exec($sql)                : execute une requete de modification (INSERT/UPDATE/DELETE)
 */
class Database
{
    // Propriete statique qui stocke l'instance unique de la connexion PDO.
    // Elle est partagee par tous les appels a Database::connection().
    private static $connection = null;

    /**
     * Retourne la connexion PDO unique.
     * Si la connexion n'existe pas encore, elle est creee a partir du fichier de config.
     * Si elle existe deja, la meme instance est reutilisee.
     */
    public static function connection()
    {
        // Verifier si la connexion existe deja pour eviter d'en creer une nouvelle.
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        // Charger les parametres de connexion depuis app/Config/config.php.
        $config = require dirname(__DIR__) . '/Config/config.php';
        $db = $config['db'];

        try {
            // Creer la connexion PDO avec les parametres lus depuis la configuration.
            // ERRMODE_EXCEPTION : PDO lance une exception en cas d'erreur SQL.
            // CASE_LOWER        : les noms de colonnes sont toujours en minuscules.
            // FETCH_ASSOC       : les resultats sont retournes sous forme de tableaux associatifs.
            self::$connection = new PDO($db['dsn'], $db['user'], $db['password'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_CASE               => PDO::CASE_LOWER,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $exception) {
            // En cas d'echec de connexion, arreter l'application avec un message d'erreur.
            http_response_code(500);
            exit('Erreur de connexion a la base de donnees : ' . $exception->getMessage());
        }

        return self::$connection;
    }
}
