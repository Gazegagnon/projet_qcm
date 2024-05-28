<?php

namespace App\Model;
use App\Entity\Question;

class QuestionModel extends Model

{
    public function create($question)
    {
        $sql ="INSERT INTO `question` VALUES(NULL, :libelle, :points ,:idQcm)";
        $this->executereq($sql,[
            "libelle" => $question->getLibelle(),
            "points" => $question->getPoints(),
            "idQcm" => $question->getIdQcm()
        ]);
    }


    public function questions($idens)
    {
        $sql = "SELECT * FROM question ";
        $stmt = $this->executereq($sql);
        $tab = [];

        while($resultat = $stmt->fetch())
        {
            extract($resultat);
            $tab[] = new Question($id, $libelle, $points, $idQcm)  ;
        }
        return $tab;
    }

    public function getQuestionByQcm($idqcm)
    {
        $sql = "SELECT * FROM question WHERE idQcm = :id ";
        $stmt = $this->executereq($sql, ["id" => $idqcm]);
        
        $tab = [];

        while($resultat = $stmt->fetch())
        {
            extract($resultat);
            $tab[] = new Question($id, $libelle, $points, $idQcm)  ;
        }
        return $tab;

    }

}