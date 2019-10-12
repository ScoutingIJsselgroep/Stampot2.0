@extends('layouts.app')

@section('content')
<div class="container">
  @if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
  @endif
  <h3 class="mt-5 mb-4 text-center">Stamlijst vandaag</h3>
  <table class="table">
    <thead>
      <tr>
        <th scope="col">Persoon</th>
        <th scope="col">Aantal</th>
        <th scope="col">Product</th>
        <th scope="col">Functies</th>
      </tr>
    </thead>
    <tbody>
    @if (count($consuming_users) === 0)
      Voeg stamleden toe door iets voor ze te bestellen.
    @endif
    @foreach($consuming_users as $user)
    <?php if ($user->sum_amount <= 0) {
      continue;
    }?>
    <tr>
      <td>
        @if ($user->user_icon !== Null)
          <img src="{{Storage::disk('public')->url($user->user_icon)}}" style="max-height: 25px; max-width:25px;" class="card-img rounded-circle" alt="{{$user->user_icon}}"> {{$user->name}}
        @else
          <img src="{{ Avatar::create($user->name)->toBase64()}}" style="max-height: 25px; max-width:25px;" class="card-img rounded-circle" alt=""> {{$user->name}}
        @endif
      </td>
      <td>
        {{$user->sum_amount}}
      </td>
      <td>
        <img src="{{Storage::disk('public')->url($user->product_icon)}}" style="max-height: 25px; max-width:25px;" class="card-img rounded-circle" alt="{{$user->product_icon}}"> {{$user->product_name}}
      </td>
      <td>
        <div style="display:inline-block;">
          <form action = "{{ route('transactions/insert') }}" method = "post">
            <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
            <input type = "hidden" name = "user_id" value = "{{$user->id}}">
            <input type = "hidden" name = "product_id" value = "{{$user->product_id}}">
            <input type = "hidden" name = "amount" value = "1">
            <button type="submit" class="btn btn-success">+</button>
          </form>
        </div>
        <!--
        <div style="display:inline-block;">
          <form action = "{{ route('transactions/insert') }}" method = "post">
            <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
            <input type = "hidden" name = "user_id" value = "{{$user->id}}">
            <input type = "hidden" name = "product_id" value = "{{$user->product_id}}">
            <input type = "hidden" name = "amount" value = "-1">
            <button type="submit" class="btn btn-danger"><i class="fa fa-minus"></i></button>
          </form>-->
        </div>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
<div style="margin: 0 auto;">
  {{ $consuming_users->links() }}
</div>
<h3 class="mt-5 mb-4 text-center">Stamlid toevoegen</h3>
<form action = "{{ route('tally/rows') }}" method="get">
  <div class="input-group mb-3">
      <input type="text" class="form-control" name="query" value="{{$query}}"placeholder="Zoeken" aria-label="Zoeken" aria-describedby="zoeken">
      <div class="input-group-append">
        <button class="btn btn-primary" type="submit" id="zoeken">Zoeken</button>
        @if (\Auth::user()->admin == 1)
        <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#usermodal"><i class="fa fa-user"></i> Nieuw <span class="hidden-xs">Stamlid</span></button>
        @endif
      </div>
  </div>
</form>
<table class="table">
  <thead>
    <tr>
      <th scope="col">Persoon</th>
      @foreach($products as $product)
        <th scope="col">{{$product->name}}</th>
      @endforeach
    </tr>
  </thead>
  <tbody>
  @if (count($idle_users) === 0)
    Geen gebruikers.
  @endif
  @foreach($idle_users as $user)
  <tr>
    <td>
      @if ($user->user_icon !== Null)
        <img src="{{Storage::disk('public')->url($user->user_icon)}}" style="max-height: 25px; max-width:25px;" class="card-img" alt="{{$user->user_icon}}"> {{$user->name}}
      @else
        <img src="{{ Avatar::create($user->name)->toBase64()}}" style="max-height: 25px; max-width:25px;" class="card-img" alt=""> {{$user->name}}
      @endif
    </td>
    @foreach($products as $product)
    <td>
      <div style="display:inline-block;">
        <form action = "{{ route('transactions/insert') }}" method = "post">
          <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
          <input type = "hidden" name = "user_id" value = "{{$user->id}}">
          <input type = "hidden" name = "product_id" value = "{{$product->id}}">
          <input type = "hidden" name = "amount" value = "1">
          <button type="submit" class="btn btn-primary">Toevoegen</button>
        </form>
      </div>
    </td>
    @endforeach
  </tr>
  @endforeach
  </tbody>
</table>
<div style="margin: 0 auto;">
  {{ $idle_users->links() }}
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
