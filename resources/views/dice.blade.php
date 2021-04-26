<x-layout>
    <x-slot name="content">
    <h1>Roll a die</h1>
    <p>{!! $message !!}<p>

    <form method="POST" action="dice">
        @csrf <!-- prevent 419 page expired -->
        <input type="submit" name="dieRoll" value="Roll die">
    </form>

    <p class="{{ $roll }}"></p>
    </x-slot>
</x-layout>


