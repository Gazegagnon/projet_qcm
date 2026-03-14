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

    public function countResultatByEleve($idEleve)
    {
        $sql = "SELECT COUNT(*) AS total FROM resultat WHERE idEleve = :idEleve";
        $stmt = $this->executereq($sql, ["idEleve" => $idEleve]);
        $resultat = $stmt->fetch();

        return $resultat ? (int)$resultat['total'] : 0;
    }

    public function getDernierResultatByEleve($idEleve)
    {
        $sql = "SELECT * FROM resultat WHERE idEleve = :idEleve ORDER BY dateResultat DESC LIMIT 1";
        $stmt = $this->executereq($sql, ["idEleve" => $idEleve]);
        $resultat = $stmt->fetch();

        if (!$resultat) {
            return false;
        }

        return new Resultat(
            $resultat['id'],
            $resultat['score'],
            $resultat['total'],
            $resultat['idEleve'],
            $resultat['idQcm'],
            $resultat['dateResultat']
        );
    }

    public function getMoyenneByEleve($idEleve)
    {
        $sql = "SELECT AVG((score / total) * 100) AS moyenne FROM resultat WHERE idEleve = :idEleve AND total > 0";
        $stmt = $this->executereq($sql, ["idEleve" => $idEleve]);
        $resultat = $stmt->fetch();

        return $resultat && $resultat['moyenne'] !== null ? round($resultat['moyenne'], 2) : 0;
    }

    public function getMeilleurScore($idEleve)
    {
        $sql = "SELECT MAX((score / total) * 100) AS meilleur FROM resultat WHERE idEleve = :idEleve";
        $stmt = $this->executereq($sql, ["idEleve" => $idEleve]);
        $res = $stmt->fetch();

        return $res && $res['meilleur'] ? round($res['meilleur'], 2) : 0;
    }

    public function getPireScore($idEleve)
    {
        $sql = "SELECT MIN((score / total) * 100) AS pire FROM resultat WHERE idEleve = :idEleve";
        $stmt = $this->executereq($sql, ["idEleve" => $idEleve]);
        $res = $stmt->fetch();

        return $res && $res['pire'] ? round($res['pire'], 2) : 0;
    }

    public function countReussis($idEleve)
    {
        $sql = "SELECT COUNT(*) AS total FROM resultat WHERE idEleve = :idEleve AND score >= (total * 0.5)";
        $stmt = $this->executereq($sql, ["idEleve" => $idEleve]);
        $res = $stmt->fetch();

        return $res ? (int)$res['total'] : 0;
    }

    public function countEchoues($idEleve)
    {
        $sql = "SELECT COUNT(*) AS total FROM resultat WHERE idEleve = :idEleve AND score < (total * 0.5)";
        $stmt = $this->executereq($sql, ["idEleve" => $idEleve]);
        $res = $stmt->fetch();

        return $res ? (int)$res['total'] : 0;
    }

    public function getResultatByEleveAndQcm($idEleve, $idQcm)
    {
        $sql = "SELECT * FROM resultat WHERE idEleve = :idEleve AND idQcm = :idQcm LIMIT 1";
        $stmt = $this->executereq($sql, [
            "idEleve" => $idEleve,
            "idQcm" => $idQcm
        ]);

        $resultat = $stmt->fetch();

        if (!$resultat) {
            return false;
        }

        return new Resultat(
            $resultat['id'],
            $resultat['score'],
            $resultat['total'],
            $resultat['idEleve'],
            $resultat['idQcm'],
            $resultat['dateResultat']
        );
    }
}