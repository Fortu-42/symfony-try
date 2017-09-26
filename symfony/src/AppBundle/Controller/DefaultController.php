<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class DefaultController extends Controller
{
  
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    
     public function pruebasAction(Request $request)
     {
        $em =  $this->getDoctrine()->getManager();
        $users = $em->getRepository('BackendBundle:User')->findAll();
        
        return $this->json($users);

     }

     public function json($data){
        $normalizers = array(new ObjectNormalizer());
        $encoders = array(new JsonEncoder());
        $serializer = new Serializer($normalizers, $encoders);
        $json = $serializer->serialize($data, 'json');

        $response = new Response(
            $response->setContent($json),
            Response::HTTP_OK,
            array('content-type' => 'appplication/json')
        );
           
        return $response;
    }
}
