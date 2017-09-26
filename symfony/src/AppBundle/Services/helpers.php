<?php 
namespace AppBundle\Services;

class helpers{
    
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




?>