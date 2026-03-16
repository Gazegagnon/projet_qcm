<?php

namespace App\Model;

use App\Entity\Qcm;

class QcmModel extends Model
{
    public function create($qcm)
    {
        $sql = "INSERT INTO qcm (theme, idEnseignant, statut) VALUES (:theme, :idEn, :statut)";

        $this->executereq($sql, [
            "theme" => $qcm->getTheme(),
            "idEn"  => $qcm->getIdEnseignant(),
            "statut" => $qcm->getStatut()
        ]);

        $id = $this->getPdo()->lastInsertId();

        return new Qcm(
            $id,
            $qcm->getTheme(),
            $qcm->getIdEnseignant(),
            $qcm->getStatut()
        );
    }

    public function qcms()
    {
        $sql = "SELECT * FROM qcm";
        $stmt = $this->executereq($sql);
        $tab = [];

        while ($resultat = $stmt->fetch()) {
            $tab[] = new Qcm(
                $resultat['id'],
                $resultat['theme'],
                $resultat['idEnseignant'],
                $resultat['statut'] ?? 'actif'
            );
        }

        return $tab;
    }

    public function getQcmByEns($idEns)
    {
        $sql = "SELECT * FROM qcm WHERE idEnseignant = :idens";
        $stmt = $this->executereq($sql, ["idens" => $idEns]);
        $tab = [];

        while ($resultat = $stmt->fetch()) {
            $tab[] = new Qcm(
                $resultat['id'],
                $resultat['theme'],
                $resultat['idEnseignant'],
                $resultat['statut'] ?? 'actif'
            );
        }

        return $tab;
    }

    public function Qcm($id)
    {
        $sql = "SELECT * FROM qcm WHERE id = :id";
        $stmt = $this->executereq($sql, ["id" => $id]);
        $resultat = $stmt->fetch();

        if (!$resultat) {
            return false;
        }

        return new Qcm(
            $resultat['id'],
            $resultat['theme'],
            $resultat['idEnseignant'],
            $resultat['statut'] ?? 'actif'
        );
    }

    public function getNomEnseignantByQcm($idQcm)
    {
        $sql = "SELECT e.nom 
                FROM qcm q
                INNER JOIN enseignant e ON q.idEnseignant = e.id
                WHERE q.id = :idQcm";

        $stmt = $this->executereq($sql, ["idQcm" => $idQcm]);
        $resultat = $stmt->fetch();

        return $resultat ? $resultat['nom'] : "Inconnu";
    }
}