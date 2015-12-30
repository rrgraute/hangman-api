<?php
namespace HangmanBundle\service;

class GameLogic{

    protected $em;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    public function get_random_word() {
        //get the lowest and highest word ID from the database so we can generate a number.
        $lowest_id = $this->getMin();
        $highest_id = $this->getMax();
        //generate random number to query a random id of a word
        $random_word_id = rand($lowest_id, $highest_id);

        $word = $this->em
        ->getRepository("HangmanBundle:word")
        ->findOneById($random_word_id)
        ->getWord();

        if($word) {
            return $word;
        } else {
            return false;
        }
    }

    public function word_progress($word_guessing, $guessed_words = false) {

        if($guessed_words) {

        } else {
            $string_length = strlen($word_guessing);
            $new_word_progress = "";
            for($i = 1; $i <= $string_length; $i++) {
                $new_word_progress .= ".";
            }
            return $new_word_progress;
        }
    }

    public function session_status($session_info, $word_progress) {
        $game_status = array();
        $game_status["word"] = $word_progress;
        $game_status["tries_left"] = $session_info->getTriesLeft();
        $game_status["status"] = $session_info->getStatus();

        return $game_status;
    }


    public function getMax()
    {
        $repository = $this->em
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
        $repository = $this->em
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

    public function valid_guess($guess) {
        if(is_string($guess) && strlen($guess) === 1 && preg_match("/^[a-z]+$/", $guess)) {
            return true;
        }
        else {
            return false;
        }
    }

    public function already_guessed($session_info, $guess) {
        $unique_id = $session_info->getUniqueId();
        $guessed_words = $this->em
        ->getRepository("HangmanBundle:guess")
        ->findBySessionUniqueId($unique_id);

        if( !empty($guessed_words) ) {
            $already_guessed = array();
            foreach ($guessed_words as $guessed_word) {
                $already_guessed[] = $guessed_word->getGuess();
            }
            //do we have guessed this word already?
            if( in_array($guess, $already_guessed) ) {
                return true;
            } else {
                return false;
            }
        }
    }
}
