<?php

namespace scrclub\SCRClubBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use scrclub\CMSBundle\Controller\SiteController;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends SiteController
{
    public function indexAction()
    {


        // redirect to first section



        $nodes = $this->getRootNodes();

        $em         = $this->getDoctrine()->getManager();
        $configs    = $em->getRepository('scrclubCMSBundle:Config')->findAll();
        $config     = null;
        if (isset($configs[0])) {
            $config = $configs[0];
        }





        $googleAnalyticsEmbed = $this->getAnalytics();

        $langs = $this->getLangs();

        return $this->render('scrclubSCRClubBundle:default:home.html.twig', array(
            'nodes' => $nodes,
            'analyticsEmbed' => $googleAnalyticsEmbed,
            'langs' => $langs

        ));



    }

    public function welcomeAction () {

        return $this->render('scrclubSCRClubBundle:default:home.html.twig', array('intro' => true));


    }

    public function pageAction ($slug) {

        $googleAnalyticsEmbed = $this->getAnalytics();
        $langs = $this->getLangs();

        // get node


        $nodes = $this->getRootNodes();
        $node = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Node')->findOneBySlug($slug);

        if(!$node) {
            echo "Error";
            exit();
        }


        if($node->getAutocontent() AND $node->getChildren()->count() > 0) {

            // redirect to first child
            $children =  $node->getChildren();
            return $this->redirect($this->generateUrl('page',  array(
                'slug' =>  $children[0]->getSlug()
            )));


        }




        $categoryRelateds = $this->getRelatedByCategory($node);

        $template = $node->getTemplate();

        if(!$template) {
            $template= $node->getParent()->getTemplate();
        }

        $em         = $this->getDoctrine()->getManager();
        $configs    = $em->getRepository('scrclubCMSBundle:Config')->findAll();
        $config     = null;
        if (isset($configs[0])) {
            $config = $configs[0];
        }


        return $this->render('scrclubSCRClubBundle:default:'.$template->getUrl(), array(
            'nodes' => $nodes,
            'node' => $node,
            'analyticsEmbed' => $googleAnalyticsEmbed,
            'relateds' => $categoryRelateds,
            'langs' => $langs,
            'config' => $config

        ));


    }




}
