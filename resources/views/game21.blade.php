<x-layout>
    <x-slot name="content">
    <h1>{{ $header }}</h1>

    <p>{{ $message }}</p>

    <form method=post action="game21">
    @csrf <!-- prevent 419 page expired -->
    <fieldset>
        <p>How many dice would you like to use?</p>
        <label for="dice1">One</label>
            <input type="radio" name="dice" id="dice1" value="1" required>
        <label for="dice2">Two</label>
            <input type="radio" name="dice" id="dice2" value="2" required>
            <br><br>
        <label for="bitcoin">How many of your Bitcoins would you like to bet? You have {{ $playerBitcoin }} Bitcoins.</label>
            <p><input id="bitcoin" type=number name=bitcoin min="0" step="1" required value="0"></p>
            <input type=submit value="Start game">
    </fieldset>
    </form>
    </x-slot>
</x-layout>


