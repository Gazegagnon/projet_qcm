<?php
namespace App\Model;

abstract class Model{
    private $pdo;
    public function __construct(){
        $this->pdo = new \PDO("mysql:host=127.0.0.1;dbname=qcm","root","",
    [
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
    ]);}

  public function executereq($sql, array $param=[]){
    $stmt=$this->pdo->prepare($sql);
    foreach($param as $cle=>$valeur){
        $param[$cle]=htmlentities($valeur);
    }
    $stmt->execute($param);
  }
	
}