@extends('layouts.app')

@section('content')
<div class="container">
  <div class="jumbotron">
    <h1 class="display-4">Paardenrace</h1>
    <p class="lead">Gestart op {{date('01 M Y')}}. Wie haalt als eerste de finish van 50 drankjes?</p>
  </div>

  @if (count($transaction_details) === 0)
    <td>Geen gegevens om te tonen</td>
  @endif

  @foreach ($transaction_details as $transaction_detail)
    {{$transaction_detail->user_name}}
    <div class="progress">
      <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="{{$transaction_detail->amount}}" aria-valuemin="0" aria-valuemax="50" style="width: {{$transaction_detail->amount*2}}%">{{$transaction_detail->amount}}</div>
    </div>
  @endforeach
</div>
@endsection
