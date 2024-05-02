<?php

namespace App\Entity;

class Question
{
    private $id;
    private $libelle;
    private $points;
    private $idQcm;

    public function __construct($id,  $libelle,  $points,  $idQcm)
    {
        $this->id = $id;
        $this->libelle = $libelle;
        $this->points = $points;
        $this->idQcm = $idQcm;
    }
    public function getId()
    {
        return $this->id;
    }

    public function getLibelle()
    {
        return $this->libelle;
    }

    public function getPoints()
    {
        return $this->points;
    }

    public function getIdQcm()
    {
        return $this->idQcm;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function setLibelle($libelle): void
    {
        $this->libelle = $libelle;
    }

    public function setPoints($points): void
    {
        $this->points = $points;
    }

    public function setIdQcm($idQcm): void
    {
        $this->idQcm = $idQcm;
    }
}
