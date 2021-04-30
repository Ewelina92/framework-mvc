<x-layout>
    <x-slot name="content">
    <h1>{{ $header }}</h1>

    <p>{{ $message }}</p>

    <p>Rounds played: {{ $rounds }}.</p>

    <p>Player has won {{ $playerWins }} rounds. </p>
    <p>Computer has won {{ $computerWins }} rounds. </p>

    <p>Player's bitcoin: {{ $playerBitcoin }}</p>
    <p>The computer has {{ $computerBitcoin }} bitcoins left, but will gladly write you a check if needed.</p>

    <form method=post action="game21">
    @csrf <!-- prevent 419 page expired -->
    <fieldset>
        <label for="bitcoin">How many of your Bitcoins would you like to bet? You have {{ $playerBitcoin }} Bitcoins.</label>
            <p><input id="bitcoin" type=number name=bitcoin required value="0"></p>
            <input type="hidden" name="nextRound" value="nextRound"> 
            <input type=submit value="Next round">
    </fieldset>
    </form>

    <p><a href="?reset=True">Click here to end game and reset all scores</a></p>
    </x-slot>
</x-layout>


