<?php 
namespace AppBundle\Services;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


class helpers{

    public $jwt_auth;


    public function __construct($jwt_auth){

        $this->jwt_auth = $jwt_auth;

    }

    public function authCheck($hash, $getIdentity = false){
        $jwt_auth = $this->jwt_auth;

        $auth = false;

        if($hash != null){
            if($getIdentity == false){
                $check_Token = $jwt_auth->checkToken($hash);
                if($check_Token == true){
                    $auth = true;
                }
            }else{
                $check_Token = $jwt_auth->checkToken($hash, true);
                if(is_object($check_Token)){
                    $auth = $check_Token;
                }
            }
        }

        return $auth;
    }
    
    public function jsons($data){
        
              /*  $normalizers = array(new \Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer());
                $encoders = array("json"=>new \Symfony\Component\Serializer\Encoder\JsonEncoder());
                $serializer = new \Symfony\Component\Serializer\Serializer($normalizers, $encoders);
                $json = $serializer->serialize($data, 'json');
                
                $response = new \Symfony\Component\HttpFoundation\Response();
                $response->setContent($json);
                $response->headers->set("Content-Type", "application/json");
        
                return $response;*/
        
              
                $encoders = array(new XmlEncoder(), new JsonEncoder());
                $normalizers = array(new ObjectNormalizer());
                $serializer = new Serializer($normalizers, $encoders);
        
                $json = $serializer->serialize($data, 'json');
        
           
        
                $response = new Response($json);
                $response->headers->set('Content-Type', 'application/json');
        
                return $response;
                
            }


}




?>