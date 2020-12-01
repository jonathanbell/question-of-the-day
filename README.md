# Question of the day

This is a toy app that I made in order to lift the spirts of my team during the
COVID-19 pandemic. There were initially 35 questions that we asked each other
each working day as a small group (kind of like Show and Tell). The questions
are stored in a Google Firebase database.

## Installation

1. Create a Google Firebase database and place the JSON secrets file in
   `./secrets`
1. `composer install`
1. `open http://127.0.0.1:8080 && php -S 127.0.0.1:8080` (serve the app)

## Update questions to database

1. Open `http://127.0.0.1:8080/reset.php?t=<Today's day of the week in English>`
