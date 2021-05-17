<?php

date_default_timezone_set('America/Vancouver');

require_once './vendor/autoload.php';

use App\Question;

$q = new Question();

if (date('l', $_GET['t'] ?? 0) === date('l', time())) {
  if ($q->addQuestions()) {
    echo 'New questions added! Thanks for playing!';
  } else {
    echo 'No new questions to add 😞.';
  }
} else {
  echo 'Incorrect action.';
}
