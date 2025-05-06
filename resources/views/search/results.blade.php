@extends('layouts.app') <!-- or your custom layout -->

@section('content')
    <h3>Search Results for "{{ $query }}"</h3>

    @if ($results->isEmpty())
        <p>No results found.</p>
    @else
        <ul>
            @foreach ($results as $result)
                <li>{{ $result->name }}</li> <!-- or whatever data you're showing -->
            @endforeach
        </ul>
    @endif
@endsection
