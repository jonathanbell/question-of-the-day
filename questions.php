<?php

require_once './vendor/autoload.php';

use App\Question;

$q = new Question();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $allQuestionsJson = json_encode($q->getAll());
  echo $allQuestionsJson;
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  function isValidJSON($str) {
    json_decode($str);
    return json_last_error() == JSON_ERROR_NONE;
  }

  $json_params = file_get_contents('php://input');

  if (strlen($json_params) < 1 && !isValidJSON($json_params)) {
    echo '{"error": "Invalid JSON was sent by application."}';
    exit();
  }

  $question = json_decode($json_params);

  if ($question->action === 'delete') {
    if ($q->markAnswered($question->id)) {
      echo json_encode($q->getAll());
      exit();
    }

    echo '{"error": "Could not find: '.$question->id.'"}';
  }

}
