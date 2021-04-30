<x-layout>
    <x-slot name="content">
    <h1>{{ $header }}</h1>

    <ul class="yatzy-ul">
        <li>
        <p>Current result, empty slots are available</p>
            <ul>
            <li>Ones: {{ $slot1 }}</li>
            <li>Twos: {{ $slot2 }}</li>
            <li>Threes: {{ $slot3 }}</li>
            <li>Fours: {{ $slot4 }}</li>
            <li>Fives: {{ $slot5 }}</li>
            <li>Sixes: {{ $slot6 }}</li>
            </ul>
        </li>
    </ul>

    <p>{{ $message }} {!! $diceHandRoll !!}</p>

    @if ($turn < 3)
        <form method="post" action="yatzy">
            @csrf <!-- prevent 419 page expired -->
            <p class="bold">Choose if you want to save any dice for the next roll</p>
            @for ($i = 0; $i < $amountOfDice; $i++)
                <p><input type="checkbox" name="ydice{{ $i + 1 }}" value="{{ ${"diceValue" . $i} }}">Dice with value: {{ ${"diceValue" . $i} }}</p>
            @endfor
            <p><input type="submit" name="rollAgain" value="Continue"></p></form>
    @else
        <form method="post" action="yatzy">
            @csrf <!-- prevent 419 page expired -->
            <p><input type="submit" name="checkTurnResult" value="Continue"></p>
        </form>
    @endif
    </x-slot>
</x-layout>


