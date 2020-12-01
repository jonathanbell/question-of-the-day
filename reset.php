<?php

date_default_timezone_set('America/Vancouver');

require_once './vendor/autoload.php';

use App\Question;

$q = new Question();

if (date('l', $_GET['t'] ?? 0) === date('l', time())) {
  $q->resetQuestions();
  echo 'Questions reset.';
} else {
  echo 'Incorrect day.';
}
