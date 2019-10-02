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
    <h3 class="text-center mb-4 mt-5">Recente transacties</h3>
    <div class="row">
      <table class="table">
        <thead>
          <tr>
            <th scope="col">Stamlid</th>
            <th scope="col">Omschrijving</th>
            <th scope="col">Bedrag</th>
          </tr>
        </thead>
        <tbody>
          @if (count($transactions) === 0)
            Geen transacties om te tonen
          @endif

          @foreach ($transactions as $transaction)
            <tr>
              <td scope="row">{{$transaction->user_name}}</td>
              <td scope="row">{{$transaction->description}}</td>
              <td scope="row">
                @if ($transaction->mutation >= 0)
                  <td class="text-success">€ {{$transaction->mutation}}</td>
                @else
                  <td class="text-danger">€ {{$transaction->mutation}}</td>
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
      {{$transactions->links()}}
    </div>
</div>
@endsection
