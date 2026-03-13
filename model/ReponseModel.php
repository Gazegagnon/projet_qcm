<?php

namespace App\Model;

use App\Entity\Reponse;

class ReponseModel extends Model
{
    public function create($reponse)
    {
        $sql = "INSERT INTO reponse VALUES (NULL, :reponsePropose, :bonneReponse, :idQuestion)";
        
        $this->executereq($sql, [
            "reponsePropose" => $reponse->getReponsePropose(),
            "bonneReponse"   => $reponse->getBonneReponse(),
            "idQuestion"     => $reponse->getIdQuestion()
        ]);

        $id = $this->getPdo()->lastInsertId();

        return new Reponse(
            $id,
            $reponse->getReponsePropose(),
            $reponse->getBonneReponse(),
            $reponse->getIdQuestion()
        );
    }

    public function reponses()
    {
        $sql = "SELECT * FROM reponse";
        $stmt = $this->executereq($sql);
        $tab = [];

        while ($resultat = $stmt->fetch()) {
            $tab[] = new Reponse(
                $resultat['id'],
                $resultat['reponsePropose'],
                $resultat['bonneReponse'],
                $resultat['idQuestion']
            );
        }

        return $tab;
    }

    public function getReponseByQuestion($idQuestion)
    {
        $sql = "SELECT * FROM reponse WHERE idQuestion = :idQuestion";
        $stmt = $this->executereq($sql, ["idQuestion" => $idQuestion]);
        $tab = [];

        while ($resultat = $stmt->fetch()) {
            $tab[] = new Reponse(
                $resultat['id'],
                $resultat['reponsePropose'],
                $resultat['bonneReponse'],
                $resultat['idQuestion']
            );
        }

        return $tab;
    }

    public function Reponse($id)
    {
        $sql = "SELECT * FROM reponse WHERE id = :id";
        $stmt = $this->executereq($sql, ["id" => $id]);
        $resultat = $stmt->fetch();

        if (!$resultat) {
            return false;
        }

        return new Reponse(
            $resultat['id'],
            $resultat['reponsePropose'],
            $resultat['bonneReponse'],
            $resultat['idQuestion']
        );
    }
}