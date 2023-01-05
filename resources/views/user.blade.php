@extends('layouts.app')

@section('content')
<div class="container">
  <div class="jumbotron">
    <h1 class="display-4"></h1>
    <p class="lead">.</p>
  </div>
  @if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
  @endif
  <form action = "{{ route('users/edit') }}" method="post" enctype="multipart/form-data">
    <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
    <div class="form-group">
      <label for="email">E-mailadres</label>
      <input type="email" class="form-control" name="email" id="email" aria-describedby="email"/>
    </div>
    <div class="form-group">
      <label for="user_icon">Avatar</label>
      <input type="file" class="form-control" name="user_icon" id="user_icon" aria-describedby="user_icon"/>
    </div>
    <button type="submit" class="btn btn-primary">Wijzigingen opslaan</button>
  </form>
</div>
@endsection
