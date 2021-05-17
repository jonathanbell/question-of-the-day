<?php

namespace App;

use Kreait\Firebase\Factory;
use Ramsey\Uuid\Uuid;
use stdClass;

class Question {

  const dbname = 'questions';

  /**
   * An instance of the Firebase database.
   *
   * @var \Kreait\Firebase\Database
   */
  protected $database;

  public function __construct() {
    $firebase_config_path = __DIR__.'/../secrets/firebase-config.json';

    if (file_exists($firebase_config_path)) {
      $firebase = (new Factory)->withServiceAccount($firebase_config_path);
    } else {
      if (getenv('FIREBASE_CONFIG_BASE64')) {
        file_put_contents($firebase_config_path, base64_decode(getenv('FIREBASE_CONFIG_BASE64')));
        $firebase = (new Factory)->withServiceAccount($firebase_config_path);
      } else {
        exit('Firebase credentials error');
      }
    }

    $this->database = $firebase->createDatabase();
  }

  public function get(string $qid = null) {
    if (!$qid) {
      return false;
    }

    if ($this->database->getReference(self::dbname)->getSnapshot()->hasChild($qid)) {
      return $this->database
        ->getReference(self::dbname)
        ->getChild($qid)
        ->getValue();
    }

    return false;
  }

  public function getAll(): array {
    $allQs = $this->database
      ->getReference(self::dbname)
      ->getSnapshot()
      ->getValue();

    return array_map(function($key) use ($allQs) {
      $qObj = new stdClass();
      $qObj->question = $allQs[$key]['question'];
      $qObj->answered = $allQs[$key]['answered'] ?? false;
      $qObj->answeredDate = $allQs[$key]['answeredDate'] ?? null;
      $qObj->id = $key;

      return $qObj;
    }, array_keys($allQs));
  }

  public function resetQuestions(): bool {
    $oldQuestions = $this->getAll();

    foreach ($oldQuestions as $key => $value) {
      $this->remove($value->id);
    }

    $newQuestions = file(__DIR__.'/../data/questions.txt', FILE_IGNORE_NEW_LINES);

    return $this->insert($newQuestions);
  }

  /**
   * Adds a question to the collection of questions.
   *
   * @param array $questions
   * @return bool
   */
  public function insert(array $questions = []) {
    if (empty($questions)) {
      return false;
    }

    foreach ($questions as $question) {
      $uuid = Uuid::uuid4();
      $qid = $uuid->toString();

      $this->database
        ->getReference()
        ->getChild(self::dbname)
        ->getChild($qid)
        ->set([
          'question' => $question,
          'answered' => false,
          'answeredDate' => null,
        ]);
    }

    return true;
  }

  public function update(array $questionData = []) {
    if (empty($questionData) || !isset($questionData['qid'])) {
      return false;
    }

    if ($this->get($questionData['qid'])) {
      $this->database
        ->getReference()
        ->getChild(self::dbname)
        ->getChild($questionData['qid'])
        ->set([
          'question' => $questionData['question'],
          'answered' => $questionData['answered'],
          'answeredDate' => $questionData['answeredDate'] ?? null,
        ]);

      return true;
    }

    return false;
  }

  public function markAnswered(string $qid): bool {
    // Get question
    $question = $this->get($qid);

    // Update question
    $questionData = [
      'qid' => $qid,
      'question' => $question['question'],
      'answered' => true,
      'answeredDate' => time(),
    ];

    return $this->update($questionData);
  }

  public function remove(string $qid = null) {
    if (!$qid) {
      return false;
    }

    if ($this->database->getReference(self::dbname)->getSnapshot()->hasChild($qid)) {
      $this->database->getReference(self::dbname)->getChild($qid)->remove();
      return true;
    }

    return false;
  }

}
