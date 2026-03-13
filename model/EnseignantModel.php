<?php

namespace App\Model;

use App\Entity\Enseignant;

class EnseignantModel extends Model
{
    public function create($enseignant)
    {
        $sql = "INSERT INTO enseignant VALUES (NULL, :nom, :mdp, :mail)";

        $this->executereq($sql, [
            "nom"  => $enseignant->getNom(),
            "mdp"  => $enseignant->getMotDePasse(),
            "mail" => $enseignant->getEmail()
        ]);

        $id = $this->getPdo()->lastInsertId();

        return new Enseignant(
            $id,
            $enseignant->getNom(),
            $enseignant->getMotDePasse(),
            $enseignant->getEmail()
        );
    }

    public function login($mail, $mdpEns)
    {
        $sql = "SELECT * FROM enseignant WHERE motDePasse = :mdp AND email = :email";

        $stmt = $this->executereq($sql, [
            "mdp"   => $mdpEns,
            "email" => $mail
        ]);

        $resultat = $stmt->fetch();

        if ($resultat) {
            return new Enseignant(
                $resultat['id'],
                $resultat['nom'],
                $resultat['motDePasse'],
                $resultat['email']
            );
        }

        return false;
    }

    public function Enseignants()
    {
        $stmt = $this->getAll("enseignant");
        $tab = [];

        while ($resultat = $stmt->fetch()) {
            $tab[] = new Enseignant(
                $resultat['id'],
                $resultat['nom'],
                $resultat['motDePasse'],
                $resultat['email']
            );
        }

        return $tab;
    }

    public function Enseignant($id)
    {
        $stmt = $this->getOne("enseignant", $id);
        $resultat = $stmt->fetch();

        if (!$resultat) {
            return false;
        }

        return new Enseignant(
            $resultat['id'],
            $resultat['nom'],
            $resultat['motDePasse'],
            $resultat['email']
        );
    }
}