<?php 
namespace AppBundle\Services;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Firebase\JWT\JWT;
use Doctrine\ORM\EntityManagerInterface as db;


class JwtAuth {

    public $entityManager;
    public $key;

    public function __construct(db $entityManager){
        $this->entityManager = $entityManager;
        $this->key = 'clave-secreta';
    }

    public function signup($email, $password, $getHash = NULL){
         $key = $this->key;
         $user = $this->entityManager->getRepository('BackendBundle:User')->findOneBy(
             array(
                 "email"=>$email,
                 "password"=>$password
             )
         ); 

        $signup = false;


        if(is_object($user)){
            $signup = true;
        }

        if($signup == true ){
            $token = array(
                "sub" => $user->getId(),
                "email" => $user->getEmail(),
                "name" => $user->getName(),
                "surname" => $user->getSurname(),
                "password" => $user->getPassword(),
                "image" => $user -> getImage(),
                "iat" => time(),
                "exp" => time() + (7*24*60*60)
             );

             $jwt = JWT::encode($token, $key, 'HS256');
             $decoded = JWT::decode($jwt, $key, array('HS256'));

             if($getHash != null){
                 return $jwt;
             }else{
                 return $decoded;
             }
        }
         
        else{
            return array("status"=>"error", "data" => "login failed");
        }
    }
    
    public function checkToken($jwt, $getIdentity = false){
        $key = $this->key;

        $auth = false;

        try{
            $decoded = JWT::decode($jwt, $key, array('HS256'));

        }catch (\UnexpectedValueException $e){
            $auth = false;
        }catch (\DomainException $e){
            $auth = false;
        }

        if(isset($decoded->sub)){
            $auth = true;
        }else{
            $auth = false; 
        }

        if($getIdentity == true){
            return $decoded;
        }else{
            return $auth;
        }
    }
}




?>