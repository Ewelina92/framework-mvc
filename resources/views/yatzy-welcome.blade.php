<x-layout>
    <x-slot name="content">
    <h1>{{ $header }}</h1>

    <p>{{ $message }}</p>

    <form method=post action="yatzy">
        @csrf <!-- prevent 419 page expired -->
        <input type=submit name="startYatzy" value="Start game">
    </form>
    </x-slot>
</x-layout>
