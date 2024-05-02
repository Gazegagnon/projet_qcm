<?php

namespace App\Entity;

class Repondre
{
    private $idEleve;
    private $idQcm;
    private $note;

    public function __construct($idEleve,  $idQcm,  $note)
    {
        $this->idEleve = $idEleve;
        $this->idQcm = $idQcm;
        $this->note = $note;
    }

    public function getIdEleve()
    {
        return $this->idEleve;
    }

    public function getIdQcm()
    {
        return $this->idQcm;
    }

    public function getNote()
    {
        return $this->note;
    }


    public function setIdEleve($idEleve): void
    {
        $this->idEleve = $idEleve;
    }

    public function setIdQcm($idQcm): void
    {
        $this->idQcm = $idQcm;
    }

    public function setNote($note): void
    {
        $this->note = $note;
    }
}
