<?php

namespace App\Entity;

class Qcm
{
    private $id;
    private $theme;
    private $idEnseignant;

    public function __construct($id,  $theme,  $idEnseignant)
    {
        $this->id = $id;
        $this->theme = $theme;
        $this->idEnseignant = $idEnseignant;
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
}
