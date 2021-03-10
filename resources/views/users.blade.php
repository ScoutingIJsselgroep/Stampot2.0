@extends('layouts.app')

@section('content')
<div class="container">
  <div class="jumbotron">
    <h1 class="display-4">Gebruikers</h1>
    <p class="lead">Hieronder vindt je een lijst van stamleden.</p>
    <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#usermodal"><i class="fa fa-user"></i> Nieuw Stamlid</button>

    <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#betalingsherinnering"><i class="fa fa-money"></i> Verstuur Betalingsherinnering</button>
  </div>
  @if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
  @endif
  <form action = "{{ route('users') }}" method="get">
    <div class="input-group mb-3">
        <input type="text" class="form-control" name="query" value="{{$query}}" placeholder="Zoeken" aria-label="Zoeken" aria-describedby="zoeken">
        <div class="input-group-append">
          <button class="btn btn-primary" type="submit" id="zoeken">Zoeken</button>
        </div>
    </div>
  </form>
  <table class="table">
    <thead>
      <tr>
        <th scope="col">Persoon</th>
        <th scope="col">Saldo</th>
      </tr>
    </thead>
    <tbody>
    @if (count($users) === 0)
      Geen gebruikers.
    @endif
    @foreach($users as $user)
    <tr>
      <td>
        <img src="{{ Avatar::create($user->name)->toBase64()}}" style="max-height: 25px; max-width:25px;" class="card-img" alt=""> {{$user->name}}
      </td>
      <td>
        <div class="col-lg-3 col-md-6">
          @if ($user->balance >= 0)
            <span class="text-success">€ {{$user->balance}}</span>
          @else
            <span class="text-danger">€ {{$user->balance}}</span>
          @endif
        </div>
      </td>
    </tr>
    @endforeach
    </tbody>
  </table>
  <div style="margin: 0 auto;">
    {{ $users->links() }}
  </div>
</div>
@endsection

@section('modal')
<div class="modal fade" id="betalingsherinnering" tabindex="-1" role="dialog" aria-labelledby="betalingsherinneringlabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action = "{{ route('users/invoice') }}" method="post" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="betalingsherinneringlabel">Verstuur betalingsherinnering</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
            Je staat op het punt om een betalingsherinnering te sturen aan alle stamleden die een negatief saldo hebben.
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger">Betalingsherinnering sturen</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuleren</button>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="usermodal" tabindex="-1" role="dialog" aria-labelledby="usermodallabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action = "{{ route('users/insert') }}" method="post" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="usermodallabel">Stamlid toevoegen</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
            <div class="form-group">
              <label for="name">Naam</label>
              <input type="text" class="form-control" name="name" id="name" aria-describedby="name" placeholder="Bernard Hinault">
            </div>
            <div class="form-group">
              <label for="email">E-mail</label>
              <input type="email" class="form-control" name="email" id="email" aria-describedby="email" placeholder="b.hinault@gmail.com">
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
