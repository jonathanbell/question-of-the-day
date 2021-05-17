# Question of the day

This is a toy app that I made in order to lift the spirts of my team during the
COVID-19 pandemic. There were initially 35 questions that we asked each other
each working day as a small group (kind of like Show and Tell). The questions
are stored in a Google Firebase database.

## Installation (for local development)

1. Create a Google Firebase database and place the [JSON service account
   file](https://firebase.google.com/docs/admin/setup#add_firebase_to_your_app)
   in `./secrets/firebase-config.json`
1. `composer install`
1. `open http://127.0.0.1:8080 && php -S 127.0.0.1:8080` (serve the app)

## Add new questions to the database

1. Add the new questions (line by line) to: `data/questions.txt`
1. Open `http://<app domain>:<app port>/importnewquestions.php?day=<Today's day
   of the week in English>`
