@extends('layouts.app')

@section('content')
<div class="container">
  <div class="jumbotron">
    <h1 class="display-4">Streeplijst</h1>
    <p class="lead">Hieronder vindt je de streeplijst.</p>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#usermodal">Stamlid toevoegen</button>
  </div>
  @if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
  @endif
  <form action = "{{ route('tally') }}" method="get">
    <div class="input-group mb-3">
        <input type="text" class="form-control" name="query" value="{{$query}}"placeholder="Zoeken" aria-label="Zoeken" aria-describedby="zoeken">
        <div class="input-group-append">
          <button class="btn btn-outline-secondary" type="submit" id="zoeken">Zoeken</button>
        </div>
    </div>
  <form>
  <div class="row">
    @foreach($users as $user)
      <div class="col-md-3 col-sm-4">
        <div class="card">
          <img src="{{ Avatar::create($user->name.'a')->setTheme('colorful')->toBase64()}}" class="card-img-top" alt="">
          <div class="card-body">
            @if ($user->balance >= 0)
              <h5 class="float-right text-success">{{$user->balance}}</h5>
            @else
              <h5 class="float-right text-danger">{{$user->balance}}</h5>
            @endif
            <h5 class="card-title">{{$user->name}}</h5>
            <div class="btn-group" role="group" aria-label="Keuzes">
              @foreach($products as $product)
                <form action = "{{ route('transactions/insert') }}" method = "post">
                  <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
                  <input type = "hidden" name = "user_id" value = "{{$user->id}}">
                  <input type = "hidden" name = "product_id" value = "{{$product->id}}">
                  <input type = "hidden" name = "amount" value = "1">
                  <button type="submit" class="btn btn-primary">{{$product->name}}</button>
                </form>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    @endforeach
    {{ $users->links() }}
  </div>
</div>
@endsection


@section('modal')
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
            <div class="form-group">
              <label for="user_icon">Avatar</label>
              <input type="file" class="form-control" name="user_icon" id="user_icon" aria-describedby="user_icon"/>
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
