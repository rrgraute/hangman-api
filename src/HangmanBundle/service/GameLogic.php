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

    public function word_progress($word_guessing, $word_progress = false) {
        if($word_guessing && !$word_progress) {
            $string_length = strlen($word_guessing);
            $new_word_progress = "";
            for($i = 1; $i <= $string_length; $i++) {
                $new_word_progress .= ".";
            }
            return $new_word_progress;
        } elseif($word_guessing && is_array($word_progress)) {
            $guessing_word = explode($word_guessing);
            print_r($guessing_word); exit;
        }
    }

    public function do_guess($session_info, $guess) {
        //do we have guessed words?
        $unique_id = $session_info->getUniqueId();
        $guessed_words = $this->em
        ->getRepository("HangmanBundle:guess")
        ->findBySessionUniqueId($unique_id);

        print_r($guessed_words); exit;
        if( !empty($guessed_words) ) {
        }
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
}
