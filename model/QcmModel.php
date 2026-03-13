<?php

namespace App\Model;

use App\Entity\Qcm;

class QcmModel extends Model
{
    public function create($qcm)
    {
        $sql = "INSERT INTO qcm VALUES (NULL, :theme, :idEn)";

        $this->executereq($sql, [
            "theme" => $qcm->getTheme(),
            "idEn"  => $qcm->getIdEnseignant()
        ]);

        $id = $this->getPdo()->lastInsertId();

        return new Qcm(
            $id,
            $qcm->getTheme(),
            $qcm->getIdEnseignant()
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
                $resultat['idEnseignant']
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
                $resultat['idEnseignant']
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
            $resultat['idEnseignant']
        );
    }
}