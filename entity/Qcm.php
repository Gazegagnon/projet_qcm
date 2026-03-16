<?php

namespace App\Entity;

class Qcm
{
    private $id;
    private $theme;
    private $idEnseignant;
    private $statut;

    public function __construct($id, $theme, $idEnseignant, $statut = 'actif')
    {
        $this->id = $id;
        $this->theme = $theme;
        $this->idEnseignant = $idEnseignant;
        $this->statut = $statut;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTheme()
    {
        return $this->theme;
    }

    public function getIdEnseignant()
    {
        return $this->idEnseignant;
    }

    public function getStatut()
    {
        return $this->statut;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function setTheme($theme): void
    {
        $this->theme = $theme;
    }

    public function setIdEnseignant($idEnseignant): void
    {
        $this->idEnseignant = $idEnseignant;
    }

    public function setStatut($statut): void
    {
        $this->statut = $statut;
    }
}