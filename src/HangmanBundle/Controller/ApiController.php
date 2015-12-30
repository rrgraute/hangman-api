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
                $new_game_info = $game_logic->get_session_raport($session, $game_logic->word_progress( $session->getWord() ) );
                return new JsonResponse($new_game_info);
            }
        }
        return false;
    }

    /**
    * @Route("/api/guess/{user_id}/{guess}")
    * @Method("PUT")
    */
    public function guessAction($user_id, $guess)
    {
        $game_logic = $this->get("game_logic");
        $em = $this->getDoctrine()->getManager();
        if( $game_logic->valid_guess($guess) ) {
            $session_info = $em->getRepository("HangmanBundle:session")->findOneByUniqueId( (string)$user_id );

            if ($session_info) {
                $tries_left = $session_info->getTriesLeft();
                if($session_info->getStatus() !== "busy") {
                    return new JsonResponse( $game_logic->get_session_raport($session_info, false) );
                }
                if($tries_left > 0) {
                    if( !$game_logic->already_guessed($session_info, $guess) ) {
                        $new_guess = new guess();
                        $new_guess->setGuess($guess);
                        $new_guess->setSessionUniqueId( $session_info->getUniqueId() );
                        $apply_guess = $this->getDoctrine()->getManager();
                        $apply_guess->persist($new_guess);
                        $apply_guess->flush();
                        if($apply_guess) {
                            if( !$game_logic->guessed_correct($session_info->getWord(), $guess) ) {
                                $session_info->setTriesLeft(--$tries_left);
                                if($session_info->getTriesLeft() === 0) {
                                    $session_info->setStatus("failed");
                                    $em->flush();
                                    $game_status_report = $game_logic->get_session_raport($session_info);
                                    return new JsonResponse($game_status_report);
                                }
                                $em->flush();
                            }
                            $already_guessed_array = $game_logic->get_already_guessed($session_info);
                            $word_progress = $game_logic->word_progress( $session_info->getWord(), $already_guessed_array);
                            if( $game_logic->is_word_guessed($word_progress, $session_info->getWord() ) ) {
                                $session_info->setStatus("success");
                                $em->flush();
                            }
                            $game_status_report = $game_logic->get_session_raport($session_info, $word_progress);
                            return new JsonResponse($game_status_report);
                        } else {
                            return new JsonResponse( array("error" => "Please try again") );
                        }
                    } else {
                        return new JsonResponse(array("error" => "already guessed") );
                    }
                }
            } else {
                return new JsonResponse( array("error" => "session not found") );
            }
        } else{
            return new JsonResponse( array("error" => "not a valid entery") );
        }
    }
}
