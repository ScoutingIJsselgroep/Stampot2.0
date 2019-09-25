@extends('layouts.app')

@section('content')
<div class="container">
  <div class="jumbotron">
    <h1 class="display-4">Transacties</h1>
    <p class="lead">Transactielijst per datum.</p>
  </div>
  @if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
  @endif
  <table class="table">
    <thead>
      <tr>
        <th scope="col">Datum</th>
        <th scope="col">Aantal</th>
        <th scope="col">Details</th>
      </tr>
    </thead>
    <tbody>
      @if (count($transaction_dates) === 0)
        Geen transacties om te tonen
      @endif

      @foreach ($transaction_dates as $transaction_date)
        <tr>
          <td scope="row">{{$transaction_date->date}}</td>
          <td scope="row">{{$transaction_date->num_transactions}}</td>
          <td scope="row"><a class="btn btn-primary" href="{{ route('transactions/details', ['date'=>$transaction_date->date]) }}">Bekijk</a></td>
        </tr>
      @endforeach
    </tbody>
  </table>
  {{$transaction_dates->links()}}
</div>
@endsection


@section('modal')

@endsection
