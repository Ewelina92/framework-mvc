<x-layout>
    <x-slot name="content">
    <h1>{{ $header }}</h1>

    <p>{{ $message }}</p>

    <p>{!! $diceHandRoll !!} The sum of this throw is: {{ $roundSum }}</p>
    <p>Your total score is: {{ $totalScorePlayer }}</p>

    <button onClick="window.location.href='game21';">Roll again</button>
    <a href="?turn=computer">Stop rolling and let the computer roll</a>

    </x-slot>
</x-layout>


