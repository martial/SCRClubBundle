<?php

    namespace scrclub\SCRClubBundle\Controller;

    use scrclub\SCRClubBundle\Entity\EPAnimation;
    use scrclub\SCRClubBundle\Entity\KiblindAnimation;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use scrclub\CMSBundle\Controller\SiteController;
    use Symfony\Component\HttpFoundation\Response;

    class KiblindController extends SiteController {

        public function indexAction () {

            //redirect('cig/site/page');
            echo 'everything seems to be fine';

            $data = $this->getDoctrine()->getRepository('scrclub\SCRClubBundle\Entity\KiblindAnimation')->findAll();

            var_dump($data);



        }


        public function saveAction ($id = NULL) {

            if(isset($id)) {
                $anim = $this->getDoctrine()->getRepository('scrclub\SCRClubBundle\Entity\KiblindAnimation')->findOneById($id);
            } else {
                $anim = new KiblindAnimation();
            }

            $request = $this->getRequest();
            $request->request->get('var');




            $anim->setName($request->request->get('name'));
            $anim->setData($request->request->get('frames'));
            $anim->setSpeed($request->request->get('speed'));

            $em = $this->getDoctrine()->getManager();
            $em->persist($anim);
            $em->flush();

            return new Response("ok");

            //$frames = explode("F", $values);

        }

        public function printXMLAction () {


            $anims = $this->getDoctrine()->getRepository('scrclub\SCRClubBundle\Entity\KiblindAnimation')->findAll();


            echo '<data>';


            foreach ($anims as $dt ) {

                $node = '<anim ';
                $node .= 'id="'.$dt->getId().'" ';
                $node .= 'name="'.$dt->getName().'" ';
                $node .= 'speed="'.$dt->getSpeed().'" ';
                $node .= '>';

                echo $node;

                $frames = explode("F", $dt->getData());

                foreach ($frames as $frame) {

                    echo '<frame>';

                    $pnts = explode(",", $frame);



                    foreach ($pnts as $pnt) {

                        $data_pnt = explode("L", $pnt);
                        $id = $data_pnt[0];

                        if (is_numeric($id) AND $id != "") {
                            echo '<pt ';
                            if(isset($data_pnt[1])) echo 'l="'.$data_pnt[1].'"';
                            echo '>'.$id.'</pt>';

                        }

                    }
                    echo '</frame>';
                }

                echo '</anim>';

            }

            echo '</data>';

            return new Response("");

        }



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
