@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">


                <div class="card-body text-center">

                    <div class="text-xl mt-10 mb-3">
                        <p class="text-base font-normal">Prepare yourself, never has there been an app so historically influencial as this.
                            Turn yourself into genuine intellectual by tracking all the books that you wish to read!
                            You will count yourself amongst the greats! Socrates, De Vinci, Cliff Claven;
                        they will have nothing on you if you use this app!</p>
                        <p class="font-bold">Please login to start using this amazing, mindblowing app!</p>
                    </div>
                    <a class="btn btn-info text-2xl px-20" href="{{ route('login') }}">Login</a>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
