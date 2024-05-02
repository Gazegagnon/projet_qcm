<?php

namespace App\Entity;

class Reponse
{
    private $id;
    private $reponsePropose;
    private $bonneReponse;
    private $idQuestion;

    public function __construct($id,  $reponsePropose,  $bonneReponse,  $idQuestion)
    {
        $this->id = $id;
        $this->reponsePropose = $reponsePropose;
        $this->bonneReponse = $bonneReponse;
        $this->idQuestion = $idQuestion;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getReponsePropose()
    {
        return $this->reponsePropose;
    }

    public function getBonneReponse()
    {
        return $this->bonneReponse;
    }

    public function getIdQuestion()
    {
        return $this->idQuestion;
    }


    public function setId($id): void
    {
        $this->id = $id;
    }

    public function setReponsePropose($reponsePropose): void
    {
        $this->reponsePropose = $reponsePropose;
    }

    public function setBonneReponse($bonneReponse): void
    {
        $this->bonneReponse = $bonneReponse;
    }

    public function setIdQuestion($idQuestion): void
    {
        $this->idQuestion = $idQuestion;
    }
}
