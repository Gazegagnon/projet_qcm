<?php

namespace App\Model;
use App\Entity\Qcm;

class QcmModel extends Model
{
    public function create($qcm)
    {
        $sql ="INSERT INTO `qcm` VALUES(NULL, :theme, :idEn)";

        $this->executereq($sql, ["theme" => $qcm->getTheme(), "idEn" => $qcm->getIdEnseignant()]);
        
    }

    public function qcms()

    {
        $sql = "SELECT * FROM qcm ";
        $stmt = $this->executereq($sql);
        // $stmt = $this->getAll("qcm");
        $tab = [];

        while($resultat = $stmt->fetch())
        {
            extract($resultat);

            $tab[] = new Qcm($id, $theme, $idEnseignant);
        }
        return $tab;



    }

    public function getQcmByEns($idEns)
    {
        $sql = "SELECT * FROM qcm WHERE idEnseignant = :idens";
        $stmt = $this->executereq($sql,["idens" => $idEns]);
        $tab = [];
        while($resultat = $stmt->fetch())
        {
            extract($resultat);
            $tab[] = new Qcm($id, $theme, $idEnseignant);

        }
        return $tab;
    }

    
}