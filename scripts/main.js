let chosenQuestion;
const question = document.getElementById('question');
const submitBtn = document.getElementById('submitBtn');
const questionAnsweredCheckbox = document.getElementById('questionanswered');
const thisQuestionSucks = document.getElementById('thisquestionsucks');
const answerquestionform = document.getElementById('answerquestion');
const answeredQuestionsContainer = document.getElementById('answeredQuestions');

function updateQuestion(questionStr) {
  question.textContent = questionStr;
}

// https://stackoverflow.com/a/6274398/1171790
function shuffle(array) {
  let counter = array.length;

  while (counter > 0) {
    let index = Math.floor(Math.random() * counter);

    counter--;

    let temp = array[counter];
    array[counter] = array[index];
    array[index] = temp;
  }

  return array;
}

function handleNoMoreQuestions() {
  updateQuestion(`Sorry, but there are no more unanswered questions! 😢`);
  const formFeilds = answerquestionform.getElementsByTagName('*');
  for (let i = 0; i < formFeilds.length; i++) {
    formFeilds[i].disabled = true;
  }
}

function updateAnsweredQuestions(questions) {
  answeredQuestionsContainer.innerHTML = '';

  const answeredQuestions = questions
    .filter(question => question.answered === true)
    .sort((a, b) => {
      let comparison = 0;
      if (a.answeredDate < b.answeredDate) {
        comparison = 1;
      } else if (a.answeredDate > b.answeredDate) {
        comparison = -1;
      }
      return comparison;
    });
  if (answeredQuestions.length) {
    document.getElementById('previouslyAnswered').innerHTML = 'Previously Answered (' + answeredQuestions.length + '/' + questions.length + ')';
    document.getElementById('previouslyAnswered').style.display = 'block';
  }

  answeredQuestions.forEach(question => {
    const li = document.createElement('li');
    li.textContent = `${question.question}`;
    if (question.answeredDate) {
      const span = document.createElement('span');
      span.textContent = `Answered: ${new Date(question.answeredDate * 1000).toLocaleDateString([navigator.language, 'en-CA'])}`;
      li.appendChild(span);
    }
    answeredQuestionsContainer.appendChild(li);
  });
}

const getQuestions = async function () {
  try {
    const response = await fetch('questions.php');
    if (response.status >= 400) {
      throw new Error(
        `Bad response from server: ${response.status} error code.`
      );
    }

    const json = await response.json();

    return json;
  } catch (err) {
    console.error(err);

    return false;
  }
};

const shuffleQuestions = async function () {
  updateQuestion('Hmmmm...');
  const questions = await getQuestions();
  const shuffledQuestions = shuffle(questions);

  chosenQuestion = shuffledQuestions
    .find(question => question.answered === false);

  if (!shuffledQuestions.length || !chosenQuestion) {
    handleNoMoreQuestions();
    updateAnsweredQuestions(questions);
    return false;
  }

  // Fancy spinny question thingy
  let promise = Promise.resolve();
  shuffledQuestions.forEach((element) => {
    promise = promise.then(function () {
      updateQuestion(element.question);

      return new Promise(function (resolve) {
        setTimeout(resolve, 50);
      });
    });
  });

  promise.then(function () {
    if (chosenQuestion) {
      updateQuestion(chosenQuestion.question);
    } else {
      handleNoMoreQuestions();
    }
    updateAnsweredQuestions(questions);
  });
}

const setQuestionAnswered = async function (questionId) {
  const data = {
    id: questionId,
    action: 'delete',
  };

  try {
    const response = await fetch('questions.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(data),
    });
    if (response.status >= 400) {
      throw new Error(
        `Bad response from server: ${response.status} error code.`
      );
    }

    const json = await response.json();
    return json;
  } catch (err) {
    console.error(err);
    return false;
  }
};

const answerQuestion = async function (question) {
  // CONFETTI!!!
  Draw(); // confetti.js

  const formFeilds = answerquestionform.getElementsByTagName('*');
  for (let i = 0; i < formFeilds.length; i++) {
    formFeilds[i].disabled = true;
  }

  const questionId = question.id;
  const questions = await setQuestionAnswered(questionId);
  const answeredQuestion = questions.find(q => q.id === questionId);

  if (typeof answeredQuestion === 'undefined' || answeredQuestion.id !== questionId) {
    console.error('Error while attempting to mark question as answered: ', answeredQuestion.id, questionId);
    return false;
  }

  updateAnsweredQuestions(questions);
  document.getElementById('playAgain').style.display = 'block';
}

shuffleQuestions();

questionAnsweredCheckbox.addEventListener('click', (e) => {
  if (e.target.checked) {
    submitBtn.removeAttribute('disabled');
  } else {
    submitBtn.setAttribute('disabled', 'disabled');
  }
});

thisQuestionSucks.addEventListener('click', (e) => {
  e.preventDefault();
  document.getElementById('questionanswered').checked = false;
  document.getElementById('submitBtn').disabled = true;
  shuffleQuestions();
});

answerquestionform.addEventListener('submit', (e) => {
  e.preventDefault();
  answerQuestion(chosenQuestion);
});
