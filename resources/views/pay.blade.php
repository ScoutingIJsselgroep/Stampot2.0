@extends('layouts.app')

@section('content')
<div class="container">
  <div class="jumbotron">
    <h1 class="display-4">Opwaarderen</h1>
    <p class="lead">Via onderstaande knop kun je gebruikers opwaarderen.</p>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#paymodal">Stamleden opwaarderen</button>
  </div>
  @if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
  @endif
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
              <td scope="row">
                 <img src="{{ Avatar::create($transaction->user_name)->toBase64()}}" style="max-height: 25px; max-width:25px;" class="card-img" alt=""> {{$transaction->user_name}}
              </td>
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

@section('modal')
<div class="modal fade" id="paymodal" tabindex="-1" role="dialog" aria-labelledby="paymodallabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action = "{{ route('pay/insert') }}" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="paymodallabel">Opwaarderen</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
          <div class="form-group">
            <label for="user_id">Naam</label>
            <select class="form-control" name="user_id[]" id="user_id" multiple data-live-search="true", title="Niemand geselecteerd">
              @foreach ($users as $user)
                <option value="{{$user->id}}">
                  {{$user->name}}
                </option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label for="description">Beschrijving</label>
            <input type="text" class="form-control" name="description" id="description" aria-describedby="description" placeholder="Opwaarderen">
          </div>
          <div class="form-group">
            <label for="amount">Bedrag</label>
            <input type="number" step=".01" class="form-control" name="amount" id="amount" aria-describedby="amount" placeholder="0.00">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Toevoegen</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuleren</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
