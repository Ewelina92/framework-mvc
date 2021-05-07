<x-layout>
    <x-slot name="content">
        <h1>Highscores in Yatzy</h1>
        <table>
            <tr>
                <th>Scores</th>
            </tr>
            @foreach ($scores as $score)
            <tr>
                <td>{{ $score->score }}</td>
            </tr>
            @endforeach
        </table>
    </x-slot>
</x-layout>
