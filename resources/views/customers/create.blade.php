@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Add Customer</div>

            <div class="card-body">
                <form method="POST" action="{{ route('customers.store') }}">

                    @csrf

                    @error('email')
                         @include('components.alert', ['slot'=>$message ])
                    @enderror

                    <div class="form-group row">

                        <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control " name="email" value="" required="" autocomplete="email" autofocus="">
                          </div>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">Name</label>

                        <div class="col-md-6">
                            <input id="password" type="text" class="form-control " name="name" required="" autocomplete="name">

                                                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                Add Customer
                            </button>
                                                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection('content')
