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
 use BackendBundle\Entity\User;


 class UserController extends Controller {

     public function newAction(Request $request){

        $helpers = $this->get("helpers_jsons");
        $jsons = $request->get("jsons", null);
        $params = json_decode($jsons);

        $data = array();


        if($jsons != null){
            $createdAt = new \Datetime("now");
            $image = null;
            $role = "user";

            $email = (isset($params->email)) ? $params->email : null;
            $name = (isset($params->name)) ? $params->name : null;
            $surname = (isset($params->surname)) ? $params->surname : null;
            $password = (isset($params->password)) ? $params->password : null;

            $emailConstraint = new Assert\Email(array('message'=>'The email "{{value}}" is not a valid email. '));
            
            $validate_email = $this->get("validator")->validate($email, $emailConstraint );

            if( $email != null && count($validate_email) == 0 &&
                $password != null && $name != null && $surname != null 
                ){
                    $user = new User();
                    $user->setCreatedAt($createdAt);
                    $user->setImage($image);
                    $user->setRole($role);
                    $user->setEmail($email);
                    $user->setName($name);
                    $user->setSurname($surname);

                    //cifrar contraseña

                    $pwd = hash('sha256',$password);

                    $user->setPassword($pwd);

                    $em = $this->getDoctrine()->getManager();
                    $isset_user = $em->getRepository("BackendBundle:User")->findBy(
                        array(
                            "email" => $email
                        )
                    );

                    if(count($isset_user) == 0){
                        $em->persist($user);
                        $em->flush();

                        $data["status"] = 'success';
                        $data["code"] = 200;
                        $data["msg"] = 'new user created';

                    }else{
                        $data = array(
                            "status"=> "error",
                            "code"=>400,
                            "msg"=>"user not created, duplicated"
                        );
                    }

                }else{
                    $data = array(
                        "status"=> "error",
                        "code"=>400,
                        "msg"=>"user not created"
                    );
                }
        }
        return new JsonResponse($data);
     }


    public function editAction(Request $request){

        $helpers = $this->get("helpers_jsons");

        $hash = $request->get("authorization", null);
        $authCheck = $helpers->authCheck($hash);

        if($authCheck == true){
            $identity = $helpers->authCheck($hash, true);

            $em = $this->getDoctrine()->getManager();

            $user = $em->getRepository("BackendBundle:User")->findOneBy(array(
                "id"=>$identity->sub
            ));

        $jsons = $request->get("jsons", null);
        $params = json_decode($jsons);

        $data = array(
            "status"=>"error",
            "code"=>400,
            "msg"=>"user not updated"
        );

        if($jsons != null){
            $createdAt = new \Datetime("now");
            $image = null;
            $role = "user";

            $email = (isset($params->email)) ? $params->email : null;
            $name = (isset($params->name)) ? $params->name : null;
            $surname = (isset($params->surname)) ? $params->surname : null;
            $password = (isset($params->password)) ? $params->password : null;

            $emailConstraint = new Assert\Email(array('message'=>'The email "{{value}}" is not a valid email. '));
            
            $validate_email = $this->get("validator")->validate($email, $emailConstraint );

            if( $email != null && count($validate_email) == 0 && 
                $name != null && $surname != null 
                ){
                    
                    $user->setCreatedAt($createdAt);
                    $user->setImage($image);
                    $user->setRole($role);
                    $user->setEmail($email);
                    $user->setName($name);
                    $user->setSurname($surname);

                    //cifrar contraseña
                if($password =! null){

                    $em = $this->getDoctrine()->getManager();
                    $password_base = $em->getRepository("BackendBundle:User")->findOneBy(
                            array(
                                "password" => $identity->password
                    ));

                    if ($password != $password_base) {
                        $pwd = hash('sha256', $password);
                        $user->setPassword($pwd);
                    }

                }

                    $em = $this->getDoctrine()->getManager();
                    $isset_user = $em->getRepository("BackendBundle:User")->findBy(
                        array(
                            "email" => $email
                        )
                    );

                    if(count($isset_user) == 0 || $identity->email == $email){
                        $em->persist($user);
                        $em->flush();

                        $data["status"] = 'success';
                        $data["code"] = 200;
                        $data["msg"] = 'new user update';

                    }else{
                        $data = array(
                            "status"=> "error",
                            "code"=>400,
                            "msg"=>"user not updated 1"
                        );
                    }

                }else{
                    $data = array(
                        "status"=> "error",
                        "code"=>400,
                        "msg"=>"user not updated 2"
                    );
                }
            }
        }else{
            $data = array(
                "status"=>"error",
                "code"=>400,
                "msg"=> "authorization not valid"
                );
        }
        return new JsonResponse($data);
     }

    public function uploadImageAction(Request $request){

        $helpers = $this->get("helpers_jsons");
        
                $hash = $request->get("authorization", null);
                $authCheck = $helpers->authCheck($hash);
        
                if($authCheck == true){
                    $identity = $helpers->authCheck($hash, true);
        
                    $em = $this->getDoctrine()->getManager();
        
                    $user = $em->getRepository("BackendBundle:User")->findOneBy(array(
                        "id"=>$identity->sub
                    ));


             $file = $request->files->get("image");

             if(!empty($file) && $file != null){
                 
                $ext = $file->guessExtension();
                $file_name = time().".".$ext;
                $file->move("uploads",$file_name);

                $user->setImage($file_name);
                $em->persist($user);
                $em->flush();
                $data = array(
                    "status"   =>"error",
                    "code"     =>200,
                    "msg"      =>"image uploaded");
             }else{
                 $data = array(
                     "status"   =>"error",
                     "code"     =>400,
                     "msg"      =>"image not uploaded"
                 );
             }


         }else{
            $data = array(
                "status"    =>"error",
                "code"      =>400,
                "msg"       =>"authorization not valid"
            );
         }

         return new JsonResponse($data);
        }

 }

 






?>