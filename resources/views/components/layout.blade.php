<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Home</title>

        <!-- Fonts & Styles -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="test.css">
    </head>
    <body>
        <header>
            <p><a href="home">Home</a></p>
            <p><a href="dice">Die</a></p>
            <p><a href="dicehand">Dicehand</a></p>
            <p><a href="game21">Game 21</a></p>
            <p><a href="yatzy">Yatzy</a></p>
        </header>
        <main>
            {{ $content }}
        </main>
        <footer>
            <p>&copy; Ewelina Jankowska for mvc spring 2021.</p>
        </footer>
    </body>
</html>

