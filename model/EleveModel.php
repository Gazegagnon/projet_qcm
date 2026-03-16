<?php

namespace App\Model;

use App\Entity\Eleve;

class EleveModel extends Model
{
    public function create($eleve)
    {
        // Version compatible avec une table simple :
        // id, nom, motDePasse, email
        $sql = "INSERT INTO eleve (nom, motDePasse, email) VALUES (:nom, :mdp, :mail)";

        $this->executereq($sql, [
            "nom"  => $eleve->getNom(),
            "mdp"  => $eleve->getMotDePasse(),
            "mail" => $eleve->getEmail()
        ]);

        $id = $this->getPdo()->lastInsertId();

        return $this->Eleve($id);
    }

    public function login($mail, $mdpEleve)
    {
        $sql = "SELECT * FROM eleve WHERE motDePasse = :mdp AND email = :email";

        $stmt = $this->executereq($sql, [
            "mdp"   => $mdpEleve,
            "email" => $mail
        ]);

        $resultat = $stmt->fetch();

        if (!$resultat) {
            return false;
        }

        return $this->buildEleve($resultat);
    }

    public function emailExiste($email)
    {
        $sql = "SELECT * FROM eleve WHERE email = :email";
        $stmt = $this->executereq($sql, ["email" => $email]);

        return $stmt->fetch() ? true : false;
    }

    public function emailExistePourAutreEleve($email, $idEleve)
    {
        $sql = "SELECT * FROM eleve WHERE email = :email AND id != :id";
        $stmt = $this->executereq($sql, [
            "email" => $email,
            "id"    => $idEleve
        ]);

        return $stmt->fetch() ? true : false;
    }

    public function update($eleve)
    {
        $sql = "UPDATE eleve 
                SET nom = :nom, motDePasse = :mdp, email = :mail
                WHERE id = :id";

        $this->executereq($sql, [
            "nom"  => $eleve->getNom(),
            "mdp"  => $eleve->getMotDePasse(),
            "mail" => $eleve->getEmail(),
            "id"   => $eleve->getId()
        ]);

        return $this->Eleve($eleve->getId());
    }

    public function Eleves()
    {
        $sql = "SELECT * FROM eleve ORDER BY nom ASC";
        $stmt = $this->executereq($sql);
        $tab = [];

        while ($resultat = $stmt->fetch()) {
            $tab[] = $this->buildEleve($resultat);
        }

        return $tab;
    }

    public function Eleve($id)
    {
        $stmt = $this->getOne("eleve", $id);
        $resultat = $stmt->fetch();

        if (!$resultat) {
            return false;
        }

        return $this->buildEleve($resultat);
    }

    /**
     * Construit un objet Eleve en restant compatible
     * avec une entité simple (4 paramètres)
     * ou enrichie (6 paramètres avec dateInscription et photo).
     */
    private function buildEleve(array $resultat): Eleve
    {
        $reflection = new \ReflectionClass(Eleve::class);
        $constructor = $reflection->getConstructor();
        $nbParams = $constructor ? $constructor->getNumberOfParameters() : 0;

        if ($nbParams >= 6) {
            return new Eleve(
                $resultat['id'],
                $resultat['nom'],
                $resultat['motDePasse'],
                $resultat['email'],
                $resultat['dateInscription'] ?? null,
                $resultat['photo'] ?? null
            );
        }

        return new Eleve(
            $resultat['id'],
            $resultat['nom'],
            $resultat['motDePasse'],
            $resultat['email']
        );
    }
}