<?php

namespace App\Entity;

class Resultat
{
    private $id;
    private $score;
    private $total;
    private $idEleve;
    private $idQcm;
    private $dateResultat;

    public function __construct($id, $score, $total, $idEleve, $idQcm, $dateResultat = null)
    {
        $this->id = $id;
        $this->score = $score;
        $this->total = $total;
        $this->idEleve = $idEleve;
        $this->idQcm = $idQcm;
        $this->dateResultat = $dateResultat;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getScore()
    {
        return $this->score;
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function getIdEleve()
    {
        return $this->idEleve;
    }

    public function getIdQcm()
    {
        return $this->idQcm;
    }

    public function getDateResultat()
    {
        return $this->dateResultat;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function setScore($score): void
    {
        $this->score = $score;
    }

    public function setTotal($total): void
    {
        $this->total = $total;
    }

    public function setIdEleve($idEleve): void
    {
        $this->idEleve = $idEleve;
    }

    public function setIdQcm($idQcm): void
    {
        $this->idQcm = $idQcm;
    }

    public function setDateResultat($dateResultat): void
    {
        $this->dateResultat = $dateResultat;
    }
}