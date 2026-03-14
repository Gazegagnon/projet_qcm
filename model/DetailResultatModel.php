<?php

namespace App\Model;

use App\Entity\DetailResultat;

class DetailResultatModel extends Model
{
    public function create($detailResultat)
    {
        $sql = "INSERT INTO detail_resultat VALUES (NULL, :idResultat, :idQuestion, :idReponseChoisie, :estCorrecte, :pointsObtenus)";

        $this->executereq($sql, [
            "idResultat" => $detailResultat->getIdResultat(),
            "idQuestion" => $detailResultat->getIdQuestion(),
            "idReponseChoisie" => $detailResultat->getIdReponseChoisie(),
            "estCorrecte" => $detailResultat->getEstCorrecte(),
            "pointsObtenus" => $detailResultat->getPointsObtenus()
        ]);

        $id = $this->getPdo()->lastInsertId();

        return new DetailResultat(
            $id,
            $detailResultat->getIdResultat(),
            $detailResultat->getIdQuestion(),
            $detailResultat->getIdReponseChoisie(),
            $detailResultat->getEstCorrecte(),
            $detailResultat->getPointsObtenus()
        );
    }

    public function getDetailsByResultat($idResultat)
    {
        $sql = "SELECT * FROM detail_resultat WHERE idResultat = :idResultat";
        $stmt = $this->executereq($sql, ["idResultat" => $idResultat]);
        $tab = [];

        while ($resultat = $stmt->fetch()) {
            $tab[] = new DetailResultat(
                $resultat['id'],
                $resultat['idResultat'],
                $resultat['idQuestion'],
                $resultat['idReponseChoisie'],
                $resultat['estCorrecte'],
                $resultat['pointsObtenus']
            );
        }

        return $tab;
    }
}