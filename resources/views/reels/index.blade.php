@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Reels</h1>
        <div class="row">
            @foreach($reels as $reel)
                <div class="col-md-4">
                    <a href="{{ route('reel.show', $reel->id) }}">
                        <video width="100%" controls>
                            <source src="{{ asset('storage/' . $reel->video) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </a>
                </div>
            @endforeach
        </div>
        {{ $reels->links() }}
    </div>
@endsection
