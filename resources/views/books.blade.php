<x-layout>
    <x-slot name="content">
        <h1>Books in database</h1>
        <table>
            <tr>
                <th>ISBN</th>
                <th>Title</th>
                <th>Author</th>
                <th>Image</th>
            </tr>
            @foreach ($books as $book)
            <tr>
                <td>{{ $book->isbn }}</td>
                <td>{{ $book->title }}</td>
                <td>{{ $book->author }}</td>
                <td><img src="{{ $book->image }}"></td>
            </tr>
            @endforeach
        </table>
    </x-slot>
</x-layout>
