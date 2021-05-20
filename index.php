<!DOCTYPE html>
<html lang="en">
<head>
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Question of the Day!">
  <!-- <meta property="og:title" content="Question of the Day!"> -->
  <meta property="og:description" content="The super fantastic afternoon standup game!">
  <meta property="og:url" content="https://fa-question-of-the-day.herokuapp.com">

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
  <title>Question of the Day!</title>

  <style>
    html {
      box-sizing: border-box;
    }
    *, *:before, *:after {
      box-sizing: inherit;
    }

    body {
      text-align: center;
      margin: auto;
      font-family: sans-serif;
      padding: 0 1rem;
      max-width: 60rem;
    }

    #question {
      min-height: 20vh;
      border: 1px solid #ccc;
      display: grid;
      align-content: center;
      padding: 0 1rem;
    }

    #answeredQuestions {
      list-style: none;
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      grid-gap: 1rem;
      padding: 1rem;
      min-height: 20vh;
    }

    #answeredQuestions li span {
      display: block;
      margin-top: 0.5rem;
      font-size: 66.666%
    }

    #previouslyAnswered, #playAgain {
      display: none;
      padding-top: 1rem;
    }

    .form-element {
      padding: 0.333rem;
    }

    /* Confetti */
    canvas {
      width: 100%;
      height: 100vh;
      margin: 0;
      position: absolute;
      top: 0;
      left: 0;
      z-index: -1;
    }
  </style>
</head>
<body>
  <canvas id="canvas"></canvas>

  <a href="/" id="playAgain">Play again?</a>

  <h1 id="question"></h1>

  <form action="answerquestion.php" method="post" id="answerquestion">
    <div class="form-element">
      <button
        id="thisquestionsucks"
      >
        This question sucks, give me another one!
      </button>
    </div>

    <div class="form-element" style="margin-top: 0.667rem">
      <input
        type="checkbox"
        id="questionanswered"
        name="questionanswered"
      >
    <label for="questionanswered">This question been answered [Y/N]?</label>
  </div>

    <div class="form-element">
      <button
        id="submitBtn"
        disabled="disabled"
        type="submit"
      >
        Mark it as answered!
      </button>
    </div>
  </form>

  <h2 id="previouslyAnswered">Previously Answered</h2>
  <ul id="answeredQuestions"></ul>

  <script src="scripts/confetti.js"></script>
  <script src="scripts/main.js"></script>

</body>
</html>
