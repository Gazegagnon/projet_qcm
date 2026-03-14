<?php

namespace App\Entity;

class DetailResultat
{
    private $id;
    private $idResultat;
    private $idQuestion;
    private $idReponseChoisie;
    private $estCorrecte;
    private $pointsObtenus;

    public function __construct($id, $idResultat, $idQuestion, $idReponseChoisie, $estCorrecte, $pointsObtenus)
    {
        $this->id = $id;
        $this->idResultat = $idResultat;
        $this->idQuestion = $idQuestion;
        $this->idReponseChoisie = $idReponseChoisie;
        $this->estCorrecte = $estCorrecte;
        $this->pointsObtenus = $pointsObtenus;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIdResultat()
    {
        return $this->idResultat;
    }

    public function getIdQuestion()
    {
        return $this->idQuestion;
    }

    public function getIdReponseChoisie()
    {
        return $this->idReponseChoisie;
    }

    public function getEstCorrecte()
    {
        return $this->estCorrecte;
    }

    public function getPointsObtenus()
    {
        return $this->pointsObtenus;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function setIdResultat($idResultat): void
    {
        $this->idResultat = $idResultat;
    }

    public function setIdQuestion($idQuestion): void
    {
        $this->idQuestion = $idQuestion;
    }

    public function setIdReponseChoisie($idReponseChoisie): void
    {
        $this->idReponseChoisie = $idReponseChoisie;
    }

    public function setEstCorrecte($estCorrecte): void
    {
        $this->estCorrecte = $estCorrecte;
    }

    public function setPointsObtenus($pointsObtenus): void
    {
        $this->pointsObtenus = $pointsObtenus;
    }
}