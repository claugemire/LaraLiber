@extends('layouts.app')

@section('content')
<div>

    @guest
    <div class="text-center">
        <div class="text-xl mt-10 mb-3">
            <p class="text-base font-normal">Prepare yourself, never has there been an app so historically influencial as this.
                Turn yourself into genuine intellectual by tracking all the books that you wish to read!
                You will count yourself amongst the greats! Socrates, De Vinci, Cliff Claven;
            they will have nothing on you if you use this app!</p>
            <p class="font-bold">Please login to start using this amazing, mindblowing app!</p>
        </div>
        <a class="btn btn-info text-2xl px-20" href="{{ route('login') }}">Login</a>
    </div>
    @endguest

    @auth
    <add-book user_id='{{ $user->id }}' api_secret='{{ $user->api_secret }}' gb_key='{{  env('API_KEY') }}' />
    @endauth

</div>

@endsection

