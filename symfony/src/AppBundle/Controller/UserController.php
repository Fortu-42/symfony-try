<?php 
 namespace AppBundle\Controller;

 
 use Symfony\Bundle\FrameworkBundle\Controller\Controller;
 use Symfony\Component\HttpFoundation\Request;
 use Symfony\Component\HttpFoundation\Response;
 use Symfony\Component\HttpFoundation\JsonResponse;
 use Symfony\Component\Validator\Mapping\ClassMetadata;
 use Symfony\Component\Validator\Constraints as Assert;
 use AppBundle\Services\helpers;
 use AppBundle\Services\JwtAuth;


 class UserController extends Controller {

     public function newAction(){
        echo "hola";
        die();
     }

 }







?>