@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Reel</h1>
        <video width="100%" controls>
            <source src="{{ asset('storage/' . $reel->video) }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <p>Uploaded by: {{ $reel->user->name }}</p>
        <!-- Add tipping, sharing, etc. here -->
    </div>
@endsection
