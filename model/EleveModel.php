<?php

namespace App\Model;

use App\Entity\Eleve;

class EleveModel extends Model
{
    public function create($eleve)
    {
        $sql = "INSERT INTO eleve VALUES (NULL, :nom, :mdp, :mail)";

        $this->executereq($sql, [
            "nom" => $eleve->getNom(),
            "mdp" => $eleve->getMotDePasse(),
            "mail" => $eleve->getEmail()
        ]);

        $id = $this->getPdo()->lastInsertId();

        return new Eleve(
            $id,
            $eleve->getNom(),
            $eleve->getMotDePasse(),
            $eleve->getEmail()
        );
    }

    public function login($mail, $mdpEleve)
    {
        $sql = "SELECT * FROM eleve WHERE motDePasse = :mdp AND email = :email";

        $stmt = $this->executereq($sql, [
            "mdp" => $mdpEleve,
            "email" => $mail
        ]);

        $resultat = $stmt->fetch();

        if ($resultat) {
            return new Eleve(
                $resultat['id'],
                $resultat['nom'],
                $resultat['motDePasse'],
                $resultat['email']
            );
        }

        return false;
    }

    public function emailExiste($email)
    {
        $sql = "SELECT * FROM eleve WHERE email = :email";
        $stmt = $this->executereq($sql, ["email" => $email]);
        return $stmt->fetch() ? true : false;
    }

    public function Eleves()
    {
        $stmt = $this->getAll("eleve");
        $tab = [];

        while ($resultat = $stmt->fetch()) {
            $tab[] = new Eleve(
                $resultat['id'],
                $resultat['nom'],
                $resultat['motDePasse'],
                $resultat['email']
            );
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

        return new Eleve(
            $resultat['id'],
            $resultat['nom'],
            $resultat['motDePasse'],
            $resultat['email']
        );
    }
}