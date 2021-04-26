<x-layout>
    <x-slot name="content">
    <h1>Roll a dicehand</h1>

    <p>{{ $message }}</p>

    <form method=post action="dicehand">
        @csrf <!-- prevent 419 page expired -->
        <input type="number" name="dice" min="1" max="10" step="1" required>
        <input type="submit" name="rollDiceHand" value="Roll">
    </form>

    {!! $roll !!}
    </x-slot>
</x-layout>


