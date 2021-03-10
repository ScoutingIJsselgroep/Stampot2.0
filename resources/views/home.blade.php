@extends('layouts.app')

@section('content')
<div class="container">
  <div class="jumbotron">
    <h1 class="display-4">Stampot</h1>
    <p class="lead">Een handige manier om de Stampot bij te houden! Onderstaande informatie biedt inzicht in <em>jouw</em> gebruik.</p>
    <a href="{{route('tally/rows')}}" class="btn btn-primary"><i class="fa fa-pencil"></i> Streeplijst</a> <a href="https://bunq.me/ijsselgroepstampot" class="btn btn-outline-secondary"><i class="fa fa-credit-card"></i> Opwaarderen</a>
  </div>
  @if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
  @endif
  <h3 class="text-center mb-4 mt-5"> Producten </h3>
  <div class="row">
    @foreach ($products as $product)
    <div class="col-md-4 col-sm-6">
      <div class="card mb-3">
        <div class="row no-gutters">
          <div class="col-md-3">
            <img src="{{Storage::disk('public')->url($product->filename)}}" class="card-img" alt="{{$product->filename}}">
          </div>
          <div class="col-md-9">
            <div class="card-body">
              <h5 class="float-right text-danger">€ {{$product->price}}</h5>
              <h5 class="card-title">{{$product->name}}</h5>
              <h6 class="card-subtitle mb-2 text-muted">{{$product->unit}}</h6>
              <form action = "{{ route('transactions/insert') }}" method = "post">
                <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
                <input type = "hidden" name = "user_id" value = "{{\Auth::user()->id}}">
                <input type = "hidden" name = "product_id" value = "{{$product->id}}">
                <input type = "hidden" name = "amount" value = "1">
                <button type="submit" class="btn btn-primary">Bestel</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endforeach
    <div style="margin: 0 auto;">
      {{ $products->links() }}
    </div>
  </div>

  <h3 class="text-center mb-4 mt-5">Mijn Statistieken</h3>
  <div class="row">
    <div class="col-lg-3 col-md-6">
      @if ($current_user->balance >= 0)
        <p class="text-center lead"><span class="display-4 text-success">€ {{$current_user->balance}}</span><br/>Saldo</p>
      @else
        <p class="text-center lead"><span class="display-4 text-danger">€ {{$current_user->balance}}</span><br/>Saldo</p>
      @endif
    </div>
    <div class="col-lg-3 col-md-6">
      <p class="text-center lead"><span class="display-4">{{$num_transactions}}</span><br/>Producten sinds epoch</p>
    </div>
    <div class="col-lg-3 col-md-6">
      <p class="text-center lead"><span class="display-4">{{$num_this_year}}</span><br/>Producten dit jaar</p>
    </div>
    <div class="col-lg-3 col-md-6">
      <p class="text-center lead"><span class="display-4 text-success">€ {{$amount_money}}</span><br/>Uitgegeven sinds epoch</p>
    </div>
  </div>


  <h3 class="text-center mb-4 mt-5"> Transacties </h3>
  <table class="table">
    <thead>
      <tr>
        <th scope="col">Product</th>
        <th scope="col">Stamlid</th>
        <th scope="col">Datum</th>
        <th scope="col">Mutatie</th>
      </tr>
    </thead>
    <tbody>
      @if (count($transactions) === 0)
        Geen transacties om te tonen
      @endif
      @foreach($transactions as $transaction)
      <tr>
        <td scope="row">{{$transaction->description}}</td>
        <td>
            <img src="{{ Avatar::create($transaction->name)->toBase64()}}" style="max-height: 25px; max-width:25px;" class="card-img" alt=""> {{$transaction->name}}
        </td>
        <td>{{$transaction->transaction_created_at}}</td>
        @if ($transaction->mutation >= 0)
          <td class="text-success">€ {{$transaction->mutation}}</td>
        @else
          <td class="text-danger">€ {{$transaction->mutation}}</td>
        @endif
      </tr>
      @endforeach
    </tbody>
  </table>
  <div style="margin: 0 auto;">
    {{ $transactions->links() }}
  </div>
</div>
@endsection
