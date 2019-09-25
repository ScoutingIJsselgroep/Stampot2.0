@extends('layouts.app')

@section('content')
<div class="container">
  <div class="jumbotron">
    <h1 class="display-4">Producten</h1>
    <p class="lead">Producten zijn items die je door kunt berekenen aan de stamleden. Denk hierbij aan drinken, snacks of een activiteit met een vaste prijs.</p>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#productmodal">Product toevoegen</button>
  </div>
  @if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
  @endif
  <div class="row">
    @if (count($products) === 0)
      Geen producten om te tonen
    @endif
    @foreach($products as $product)
    <div class="col-sm-12 col-md-4 mb-3">
      <div class="card">
        <div class="row no-gutters">
          <div class="col-md-3">
            <img src="{{Storage::disk('public')->url($product->filename)}}" class="card-img" alt="{{$product->filename}}">
          </div>
          <div class="col-md-9">
            <div class="card-body">
              <h5 class="float-right text-success">€ {{$product->price}}</h5>
              <h5 class="card-title">{{$product->name}}</h5>
              <h6 class="card-subtitle mb-2 text-muted">{{$product->unit}}</h6>
              <a href="{{ route('products/delete', ['id' => $product->id]) }}" class="btn btn-danger">Verwijder</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endforeach
    {{ $products->links() }}
  </div>
</div>
@endsection

@section('modal')
    <div class="modal fade" id="productmodal" tabindex="-1" role="dialog" aria-labelledby="productmodallabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <form action = "{{ route('products/insert') }}" method="post" enctype="multipart/form-data">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="productmodallabel">Product toevoegen</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
                <div class="form-group">
                  <label for="name">Naam</label>
                  <input type="text" class="form-control" name="name" id="name" aria-describedby="name" placeholder="Bier">
                </div>
                <div class="form-group">
                  <label for="unit">Eenheid</label>
                  <input type="text" class="form-control" name="unit" id="unit" aria-describedby="unit" placeholder="Flesje 30 cl">
                </div>
                <div class="form-group">
                  <label for="price">Prijs</label>
                  <input type="text" class="form-control" name="price" id="price" aria-describedby="price" placeholder="10.00">
                </div>
                <div class="form-group">
                  <label for="image">Afbeelding</label>
                  <input type="file" class="form-control" name="image" id="image" aria-describedby="image"/>
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
