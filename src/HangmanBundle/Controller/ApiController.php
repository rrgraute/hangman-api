<?php

namespace HangmanBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use HangmanBundle\Entity\session;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class ApiController extends Controller
{

    /**
    * @Route("/api/newgame")
    * @Method("POST")
    */
    public function newGameAction()
    {
        //get the lowest and highest ID from the database so we can generate a number.
        $lowest_id = $this->getMin();
        $highest_id = $this->getMax();
        //generate random number to query a random id of a word
        $random_word_id = rand($lowest_id, $highest_id);

        $word = $this->getDoctrine()
        ->getRepository("HangmanBundle:word")
        ->findOneById($random_word_id)
        ->getWord();
        if($word) {
            //now that we have a word. Generate a unique id for the user
            $user_id = uniqid();
            //and save the user unique id in the DB:TB session
            $session = new Session();
            $session->setUniqueId($user_id);
            $session->setWord($word);
            $session->setStatus("busy");
            $session->setTs(new \DateTime("now"));

            $entity = $this->getDoctrine()->getManager();
            $entity->persist($session);
            $entity->flush();
            if($entity) {
                //saved into DB? return the game info
                $new_game_info = array();
                $new_game_info["user_id"] = $session->getUniqueId();
                $new_game_info["status"] = $session->getStatus();
                return new JsonResponse($new_game_info);
            }
        }

        exit;
    }

    public function getMax()
    {
        $repository = $this->getDoctrine()
        ->getRepository('HangmanBundle:word');

        $highest_id = $repository->createQueryBuilder('e')
        ->select('MAX(e.id)')
        ->getQuery()
        ->getSingleScalarResult();
        if( isset($highest_id) && ! empty($highest_id) ) {
            return $highest_id;
        }
        return false;
    }

    public function getMin()
    {
        $repository = $this->getDoctrine()
        ->getRepository('HangmanBundle:word');

        $highest_id = $repository->createQueryBuilder('e')
        ->select('MIN(e.id)')
        ->getQuery()
        ->getSingleScalarResult();
        if( isset($highest_id) && ! empty($highest_id) ) {
            return $highest_id;
        }
        return false;
    }
}
