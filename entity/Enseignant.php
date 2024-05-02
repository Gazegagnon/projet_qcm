<?php

namespace App\Entity;

class Enseignant{
    private $id;
    private $nom;
    private $motDePasse;
    private $email;


    public function __construct($id,  $nom,  $motDePasse,  $email)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->motDePasse = $motDePasse;
        $this->email = $email;
    }


    public function getId()
    {
        return $this->id;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function getMotDePasse()
    {
        return $this->motDePasse;
    }

    public function getEmail()
    {
        return $this->email;
    }



    public function setId($id): void
    {
        $this->id = $id;
    }

    public function setNom($nom): void
    {
        $this->nom = $nom;
    }

    public function setMotDePasse($motDePasse): void
    {
        $this->motDePasse = $motDePasse;
    }

    public function setEmail($email): void
    {
        $this->email = $email;
    }
}