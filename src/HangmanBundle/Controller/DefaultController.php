<?php

namespace HangmanBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('HangmanBundle:Default:index.html.twig');
    }

    /**
    * @Route("/games")
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
            print_r($user_id); exit;
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
