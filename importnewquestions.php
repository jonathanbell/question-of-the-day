<?php

date_default_timezone_set('America/Vancouver');

require_once './vendor/autoload.php';

use App\Question;

$q = new Question();

if (isset($_GET['day']) && strtolower($_GET['day']) === strtolower(date('l', time()))) {
  if ($q->addQuestions()) {
    echo 'New questions added! Thanks for playing!';
  } else {
    echo 'No new questions to add 😞.';
  }
} else {
  echo 'Incorrect action.';
}
