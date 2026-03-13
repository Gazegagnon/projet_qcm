<?php

namespace App\Model;

use App\Entity\Resultat;

class ResultatModel extends Model
{
    public function create($resultat)
    {
        $sql = "INSERT INTO resultat VALUES (NULL, :score, :total, :idEleve, :idQcm, NOW())";

        $this->executereq($sql, [
            "score"   => $resultat->getScore(),
            "total"   => $resultat->getTotal(),
            "idEleve" => $resultat->getIdEleve(),
            "idQcm"   => $resultat->getIdQcm()
        ]);

        $id = $this->getPdo()->lastInsertId();

        return new Resultat(
            $id,
            $resultat->getScore(),
            $resultat->getTotal(),
            $resultat->getIdEleve(),
            $resultat->getIdQcm(),
            date('Y-m-d H:i:s')
        );
    }

    public function getResultatByEleve($idEleve)
    {
        $sql = "SELECT * FROM resultat WHERE idEleve = :idEleve ORDER BY dateResultat DESC";
        $stmt = $this->executereq($sql, ["idEleve" => $idEleve]);
        $tab = [];

        while ($resultat = $stmt->fetch()) {
            $tab[] = new Resultat(
                $resultat['id'],
                $resultat['score'],
                $resultat['total'],
                $resultat['idEleve'],
                $resultat['idQcm'],
                $resultat['dateResultat']
            );
        }

        return $tab;
    }

    public function resultatExiste($idEleve, $idQcm)
    {
        $sql = "SELECT * FROM resultat WHERE idEleve = :idEleve AND idQcm = :idQcm";
        $stmt = $this->executereq($sql, [
            "idEleve" => $idEleve,
            "idQcm"   => $idQcm
        ]);

        return $stmt->fetch() ? true : false;
    }
}