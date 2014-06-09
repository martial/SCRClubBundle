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
            'langs' => $langs,
            'config' => $config

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

        // GET NEW AND PREV

        //$prev = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Node')->findOneById($node->getLft());
        //$next = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Node')->findOneById($node->getRgt());

        $prev = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Node')->getPrevSiblings($node);
        $next = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Node')->getNextSiblings($node);

        if(count($prev) > 0 )
            $prev = $prev[0];

        if(count($next) > 0 )
            $next = $next[0];


        $tweets = array();
        if($slug =="info") {
        $twitter = $this->get("cms_bundle.twitter");
        $tweets = $twitter->getTweets("martialtwist");
        }

        //$search = $twitter->getSearch("#administration #france");


        return $this->render('scrclubSCRClubBundle:default:'.$template->getUrl(), array(
            'nodes' => $nodes,
            'node' => $node,
            'analyticsEmbed' => $googleAnalyticsEmbed,
            'relateds' => $categoryRelateds,
            'langs' => $langs,
            'config' => $config,
            'prev' => $prev,
            'next'=> $next,
            'tweets' => $tweets

        ));


    }




    public function pascaleAction () {


        $twitter = $this->get("cms_bundle.twitter");

        $admin = $twitter->getSearch("#administration #france");
        $culture = $twitter->getSearch("administration france");

        $merged = array_merge($admin->statuses, $culture->statuses);
        // get random

        $count = count($merged);
        $index = rand(0, $count -1 );

        $result = $merged[$index];


        return $this->render('scrclubSCRClubBundle:default:crochets.html.twig', array(
                'search' => $merged,
                'result' => $result

            ));


    }




}
