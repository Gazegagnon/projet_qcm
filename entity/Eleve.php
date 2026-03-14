<?php

namespace App\Entity;

class Eleve
{
    private $id;
    private $nom;
    private $motDePasse;
    private $email;
    private $dateInscription;
    private $photo;

    public function __construct($id, $nom, $motDePasse, $email, $dateInscription = null, $photo = null)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->motDePasse = $motDePasse;
        $this->email = $email;
        $this->dateInscription = $dateInscription;
        $this->photo = $photo;
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

    public function getDateInscription()
    {
        return $this->dateInscription;
    }

    public function getPhoto()
    {
        return $this->photo;
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

    public function setDateInscription($dateInscription): void
    {
        $this->dateInscription = $dateInscription;
    }

    public function setPhoto($photo): void
    {
        $this->photo = $photo;
    }
}