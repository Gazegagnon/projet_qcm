<?php

namespace App\Model;

use App\Entity\Question;

class QuestionModel extends Model
{
    public function create($question)
    {
        $sql = "INSERT INTO question VALUES (NULL, :libelle, :points, :idQcm)";
        
        $this->executereq($sql, [
            "libelle" => $question->getLibelle(),
            "points"  => $question->getPoints(),
            "idQcm"   => $question->getIdQcm()
        ]);

        $id = $this->getPdo()->lastInsertId();

        return new Question(
            $id,
            $question->getLibelle(),
            $question->getPoints(),
            $question->getIdQcm()
        );
    }

    public function questions()
    {
        $sql = "SELECT * FROM question";
        $stmt = $this->executereq($sql);
        $tab = [];

        while ($resultat = $stmt->fetch()) {
            $tab[] = new Question(
                $resultat['id'],
                $resultat['libelle'],
                $resultat['points'],
                $resultat['idQcm']
            );
        }

        return $tab;
    }

    public function getQuestionByQcm($idqcm)
    {
        $sql = "SELECT * FROM question WHERE idQcm = :id";
        $stmt = $this->executereq($sql, ["id" => $idqcm]);
        $tab = [];

        while ($resultat = $stmt->fetch()) {
            $tab[] = new Question(
                $resultat['id'],
                $resultat['libelle'],
                $resultat['points'],
                $resultat['idQcm']
            );
        }

        return $tab;
    }

    public function Question($id)
    {
        $sql = "SELECT * FROM question WHERE id = :id";
        $stmt = $this->executereq($sql, ["id" => $id]);
        $resultat = $stmt->fetch();

        if (!$resultat) {
            return false;
        }

        return new Question(
            $resultat['id'],
            $resultat['libelle'],
            $resultat['points'],
            $resultat['idQcm']
        );
    }

    public function countQuestionsByQcm($idQcm)
    {
        $sql = "SELECT COUNT(*) AS total FROM question WHERE idQcm = :idQcm";
        $stmt = $this->executereq($sql, ["idQcm" => $idQcm]);
        $result = $stmt->fetch();

        return $result ? (int)$result['total'] : 0;
    }

    public function totalPointsByQcm($idQcm)
    {
        $sql = "SELECT SUM(points) AS total FROM question WHERE idQcm = :idQcm";
        $stmt = $this->executereq($sql, ["idQcm" => $idQcm]);
        $result = $stmt->fetch();

        return $result && $result['total'] ? (int)$result['total'] : 0;
    }
}