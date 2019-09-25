@extends('layouts.app')

@section('content')
<div class="container">
  <div class="jumbotron">
    <h1 class="display-4">Betalen</h1>
    <p class="lead">Via onderstaand formulier kun je opwaarderen.</p>
  </div>
  @if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
  @endif
    <div class="row justify-content-center">
        <div class="col-md-8">
          <form action = "{{ route('pay/insert') }}" method="post">
            <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
            <div class="form-group">
              <label for="user_id">Naam</label>
              <select class="form-control" name="user_id[]" id="user_id" multiple data-live-search="true", title="Niemand geselecteerd">
                @foreach ($users as $user)
                  <option value="{{$user->id}}">{{$user->name}}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="description">Beschrijving</label>
              <input type="text" class="form-control" name="description" id="description" aria-describedby="description" placeholder="Opwaarderen">
            </div>
            <div class="form-group">
              <label for="amount">Hoeveelheid</label>
              <input type="number" step=".01" class="form-control" name="amount" id="amount" aria-describedby="amount" placeholder="0.00">
            </div>
            <button type="submit" class="btn btn-primary">Toevoegen</button>
          </form>
        </div>
    </div>
</div>
@endsection
