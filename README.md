A Symfony 3 project created on December 18, 2015, 2:37 pm.

##This is made with php version 7.0.0

Hangman API
===========


In this assignment we ask you to implement a minimal version of a hangman API using the following resources below:
####Start a new game
POST: /games
At the start of the game a random word should be picked from this list.

####Guess a started game
PUT: /games/:id
Guessing a correct letter doesn’t decrement the amount of tries left Only valid characters are a-z

####Response (JSON)
Every response should contain the following fields:
* word: representation of the word that is being guessed. Should contain dots for letters that have not been guessed yet (e.g. aw.so..)
* tries_left: the number of tries left to guess the word (starts at 11)
* status: current status of the game (busy|fail|success)

maak in Symfony 2 .. 3


Fijne feestdagen, en een goed 2016 toegewenst.
Groeten,

Henry

inleveren via mail hrobben@idcollege.nl
