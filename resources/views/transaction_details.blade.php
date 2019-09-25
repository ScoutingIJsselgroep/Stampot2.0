@extends('layouts.app')

@section('content')
<div class="container">
  <div class="jumbotron">
    <h1 class="display-4">Details</h1>
    <p class="lead">{{date_format(date_create($date),"D d F Y")}} </p>
  </div>
  @if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
  @endif
  <table class="table">
    <thead>
      <tr>
        <th scope="col">Naam</th>
        <th scope="col">Omschrijving</th>
        <th scope="col">Aantal</th>
      </tr>
    </thead>
    <tbody>
      @if (count($transaction_details) === 0)
        <td>Geen gegevens om te tonen</td>
      @endif

      @foreach ($transaction_details as $transaction_detail)
        <tr>
          <td scope="row">{{$transaction_detail->user_name}}</td>
          <td scope="row"><img src="{{Storage::disk('public')->url($transaction_detail->product_icon)}}" style="max-height: 25px; max-width:25px;" class="card-img rounded-circle" alt="{{$transaction_detail->product_icon}}"> {{$transaction_detail->product_name}}</td>
          <td scope="row">{{$transaction_detail->amount}}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
  {{$transaction_details->links()}}
</div>
@endsection


@section('modal')

@endsection
