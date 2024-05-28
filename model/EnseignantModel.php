<?php 

namespace App\Model;
use App\Entity\Enseignant;

class EnseignantModel extends Model

{
    public function create($enseignant)

    {
        $sql = "INSERT INTO `enseignant` VALUES(NULL, :nom, :mdp, :mail)";
        $this->executereq($sql, [
            "nom" => $enseignant->getNom(),
            "mdp" => $enseignant->getMotDePasse(),
            "mail" => $enseignant->getEmail()
        ]);

        $id = $this->getPdo()->lastInsertId();

        $_SESSION['enseignant'] = serialize( new Enseignant($id,$enseignant->getNom(),$enseignant->getMotDePasse(),$enseignant->getEmail() ));
        

        return $_SESSION['enseignant'];


    }


    public function login($mail, $mdpEns)
    {
        $sql = "SELECT * FROM enseignant WHERE  motDePasse = :mdp and email = :email ";
        
        $stmt =$this->executereq($sql, ["mdp" => $mdpEns, "email" => $mail]);
        // var_dump($stmt);
        
        $resultat = $stmt->fetch();

        if($resultat)
        {
            extract($resultat);
            $_SESSION['enseignant'] = serialize( new Enseignant($id, $nom,$motDePasse,$email));
            return $_SESSION['enseignant'];

        }

    }



    



    public function Enseignants()
    {
        $stmt = $this->getAll("enseignant");
        $tab = [] ;

        while($resultat = $stmt->fetch())
        {
            extract($resultat);

            $tab[] = new Enseignant($id, $nom, $motDePasse, $email);
        }

        return $tab;
    }

    public function Enseignant($id)
    {
        $stmt = $this->getOne("enseignant",$id);
        extract($stmt->fetch());
        return new Enseignant($id, $nom, $motDePasse, $email);

    }
}