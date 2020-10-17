@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <h1 class="text-3xl">User Settings</h1><hr/>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('user.update') }}" method="POST">
                {{ csrf_field() }}
                <label class="mt-3" for="name">Name:</label>
                <input type="text" name="name" id="name" value="{{ $user->name ?? '' }}" class=" form-control" min="4" />

                <label class="mt-3" for="api_secret">API Secret: <span class="text-xs">This secret will be used to access your book list over our ever-so nifty API.</span></label>
                <input type="text" name="api_secret" id="api_secret" class=" form-control" min="4" value="{{ $user->api_secret ?? '' }}" />

                <input type="submit" class="btn btn-primary mt-3 px-10" value="Save" />

            </form>


        </div>
    </div>
</div>
@endsection
