<?php 
namespace AppBundle\Services;




class helpers{
    

    private $data;
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