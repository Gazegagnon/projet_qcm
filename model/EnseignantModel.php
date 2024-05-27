<?php 

namespace App\Model;
use App\Entity\Enseignant;

class EnseignantModel extends Model

{
    public function create($enseignant)

    {
        $sql = "INSERT INTO `enseignant` VALUES(NULL, :nom, :mdp, :mail)";
        $this->executereq($sql, [
            "nom" => $enseignant->getNom(),
            "mdp" => $enseignant->getMotDePasse(),
            "mail" => $enseignant->getEmail()
        ]);

        // $id = $this->pdo->lastInsertId();

        $_SESSION['enseignant'] = serialize( new Enseignant(NULL,$enseignant->getNom(),$enseignant->getMotDePasse(),$enseignant->getEmail() ));
        

        return $_SESSION['enseignant'];


    }


    public function login($mail, $mdpEns)
    {
        // Requête SQL pour sélectionner les informations de l'enseignant
        $sql = "SELECT * FROM enseignant WHERE motDePasse = :mdp AND email = :email";

        // Exécution de la requête avec gestion des erreurs
        try {
            $stmt = $this->executereq($sql, ["mdp" => $mdpEns, "email" => $mail]);

            // Vérification si la requête a retourné un résultat
            if ($stmt === false) {
                throw new Exception("Erreur lors de l'exécution de la requête SQL.");
            }

            // Récupération du résultat
            $resultat = $stmt->fetch();

            // Vérification si un enseignant a été trouvé
            if ($resultat) {
                extract($resultat);
                $_SESSION['enseignant'] = serialize(new Enseignant($id, $nom, $motDePasse, $email));
                return $_SESSION['enseignant'];
            } else {
                // Aucun enseignant trouvé
                return null;
            }
        } catch (Exception $e) {
            // Gestion des exceptions et affichage du message d'erreur
            echo 'Erreur : ' . $e->getMessage();
            return null;
        }
    }


    public function Enseignants()
    {
        $stmt = $this->getAll("enseignant");
        $tab = [] ;

        while($resultat = $stmt->fetch())
        {
            extract($resultat);

            $tab[] = new Enseignant($id, $nom, $motDePasse, $email);
        }

        return $tab;
    }

    public function Enseignant($id)
    {
        $stmt = $this->getOne("enseignant",$id);
        extract($stmt->fetch());
        return new Enseignant($id, $nom, $motDePasse, $email);

    }
}