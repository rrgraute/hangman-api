<?php

namespace HangmanBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use HangmanBundle\Entity\session;
use HangmanBundle\Entity\guess;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class ApiController extends Controller
{

    /**
    * @Route("/api/newgame")
    * @Method("POST")
    */
    public function newGameAction()
    {
        $game_logic = $this->get("game_logic");
        $word = $game_logic->get_random_word();
        if($word) {
            //now that we have a word. Generate a unique id for the user
            $user_id = uniqid();
            //and save the user unique id in the DB:TB session
            $session = new Session();
            $session->setUniqueId($user_id);
            $session->setWord($word);
            $session->setStatus("busy");
            $session->setTriesLeft(11);
            $session->setTs(new \DateTime("now"));
            $entity = $this->getDoctrine()->getManager();
            $entity->persist($session);
            $entity->flush();
            if($entity) {
                //saved into DB? return the game info
                $new_game_info = array();
                $new_game_info["user_id"] = $session->getUniqueId();
                $new_game_info["status"] = $session->getStatus();
                $new_game_info["tries_left"] = $session->getTriesLeft();
                $new_game_info["word"] = $game_logic->word_progress($session->getWord(), false );
                return new JsonResponse($new_game_info);
            }
        }
        return false;
    }

    /**
    * @Route("/api/guess/{user_id}/{guess}")
    * @Method("GET")
    */
    public function guessAction($user_id, $guess)
    {
        $game_logic = $this->get("game_logic");
        if( $game_logic->valid_guess($guess) ) {
            $session_info = $this->getDoctrine()
            ->getRepository("HangmanBundle:session")
            ->findOneBy( array("uniqueId" => (string)$user_id, "status" => "busy") );

            if ($session_info) {
                $tries_left = $session_info->getTriesLeft();
                if($tries_left > 0) {
                    if( !$game_logic->already_guessed($session_info, $guess) ) {
                        $new_guess = new guess();
                        $new_guess->setGuess($guess);
                        $new_guess->setSessionUniqueId( $session_info->getUniqueId() );
                        $entity = $this->getDoctrine()->getManager();
                        $entity->persist($new_guess);
                        $apply_guess = $entity->flush();
                        print_r($apply_guess); exit;
                    } else {
                        return new JsonResponse( array("error") );
                    }

                } else {
                    return new JsonResponse( array("status" => "failed") );
                }
            }
        } else{
            return new JsonResponse( array("error" => "not a valid entery") );
        }
    }
}
