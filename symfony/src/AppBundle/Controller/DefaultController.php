<?php

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Services\helpers;
use AppBundle\Services\JwtAuth;

class DefaultController extends Controller
{
  
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }


    public function loginAction(Request $request)
    {

       //$JwtAuth = $this->get("jwt_auth");
       
      $jsons = $request->get("jsons", null);
       

       if($jsons != null){

           $params = json_decode($jsons);
           $email = (isset($params->email)) ? $params->email : null;
           $password = (isset($params->password)) ? $params->password : null;
           $getHash = (isset($params->gethash)) ? $params->gethash : null;
           

           $emailConstraint = new Assert\Email(array('message'=>'The email "{{value}}" is not a valid email. '));

           $validate_email = $this->get("validator")->validate($email, $emailConstraint );

           if(count($validate_email) == 0 && $password != null ){

            $JwtAuth = $this->get("jwt_auth");

            if($getHash == null){

                $signup = $JwtAuth->signup($email, $password);
              
            }else{
                $signup = $JwtAuth->signup($email, $password, true);
            }

                
            return new JsonResponse($signup);
                

               // return $signup;
           }else{
            return $helpers->jsons(array(
                "status"=> "error",
                "data"=>"Login not Valid"
            ));
           }

       }else{
           return $helpers->jsons(array(
            "status"=> "error",
            "data"=>"Send Json via POST"
        ));
       }
      
    }    

  /*  public function loginAction(Request $request){
        $helpers = $this->get("app.helpers");
    }*/
    
     public function pruebasAction(Request $request)
     {
        
        $helpers = $this->get("helpers_jsons");

        $hash = $request->get("authorization", null);

        $check = $helpers->authCheck($hash, true);

        var_dump($check);

        die();


        //$helpers = new helpers();
        
        //return $helpers->jsons($users);

     }

    
}
