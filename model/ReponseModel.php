<?php

namespace App\Model; 
use App\Entity\Reponse;

class ReponseModel extends Model
{
    public function create($reponse)
    {
        $sql ="INSERT INTO `reponse` VALUES(NULL, :reponsePropose, :bonneReponse ,:idQuestion)";
        $this->executereq($sql,[
            "reponsePropose" => $reponse->getReponsePropose(),
            "bonneReponse" => $reponse->getBonneReponse(),
            "idQuestion" => $reponse->getIdQuestion()
        ]);
    }


    public function reponses()
    {
        $sql = "SELECT * FROM reponse ";
        $stmt = $this->executereq($sql);
        $tab = [];

        while($resultat = $stmt->fetch())
        {
            extract($resultat);
            $tab[] = new Reponse($id, $reponsePropose, $bonneReponse, $idQuestion)  ;
        }
        return $tab;
    }
}