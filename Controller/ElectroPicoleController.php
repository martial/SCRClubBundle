<?php

    namespace scrclub\SCRClubBundle\Controller;

    use scrclub\SCRClubBundle\Entity\EPAnimation;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use scrclub\CMSBundle\Controller\SiteController;
    use Symfony\Component\HttpFoundation\Response;

    class ElectroPicoleController extends SiteController {


        public function uploadAction() {

            $xml = simplexml_load_file($_FILES['xml']['tmp_name']);

            $add = true;

            $EPAnimation = null;
            if( (string)$xml->config->id != "")
                $EPAnimation  = $this->getDoctrine()->getRepository('scrclub\SCRClubBundle\Entity\EPAnimation')->findOneById($xml->config->id);


            if(!$EPAnimation)
                $EPAnimation = new EPAnimation();

            if(!$add)
                $anim['id']	  = (string)($xml->config->id);


            $EPAnimation->setSpeed($xml->config->speed);


            $name = ($add) ? $this->_getUniqueName((string)$xml->config->name) : (string)$xml->config->name;

            $EPAnimation->setName($name);


            $data = "";
            $frameCnt = 0;
            foreach( $xml->frames->frame as $frame) {

                $idCnt = 0;

                foreach($frame->id as $id) {

                    $data .= $id;
                    $idCnt++;
                    if($idCnt < count($frame->id))
                        $data .= ".";
                }

                $frameCnt++;
                if($frameCnt < count($xml->frames->frame))
                    $data .= "/";
            }

            $EPAnimation->setData($data);

            $em = $this->getDoctrine()->getManager();
            $em->persist($EPAnimation);
            $em->flush();


            return new Response("ok");


        }

        public function getXMLAction() {

            //$result = $this->db->select('*')->from('ns_data')->get()->result_array();

            $result = $this->getDoctrine()->getRepository('scrclub\SCRClubBundle\Entity\EPAnimation')->findAll();

            $xml = "<root>";
            foreach ($result as $anim ) {

                $xml .= "<anim>";

                $xml .= "<config>";
                $xml .= "<id>".$anim->getId()."</id>";
                $xml .= "<name>".strtoupper($anim->getName())."</name>";
                $xml .= "<speed>".strtoupper($anim->getSpeed())."</speed>";

                $xml .= "</config>";

                $frames = explode( "/", $anim->getData());

                $xml .= "<frames>";
                foreach ( $frames as $frame ) {

                    $xml .= "<frame>";
                    $data = explode(".", $frame);
                    foreach ($data as $id) {

                        //if(!empty($id))
                        $xml .= "<id>".$id."</id>";

                    }
                    $xml .= "</frame>";

                }
                $xml .= "</frames>";


                $xml .= "</anim>";

            }
            $xml .= "</root>";

            return new Response($xml);


        }

        public function deleteAnimAction($id) {




        }

        private function _getUniqueName ($name) {

        //$result = $this->db->select('*')->from('ns_data')->get()->result_array();
         $result = $this->getDoctrine()->getRepository('scrclub\SCRClubBundle\Entity\EPAnimation')->findAll();
        $cnt = 0;
        foreach ( $result as $anim) {

            if($anim->getName() == $name)
                $cnt++;

        }

        return ($cnt == 0) ? $name : $name.$cnt;

    }





    }
