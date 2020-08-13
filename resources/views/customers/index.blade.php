@extends('layouts.app')

@section('content')

<table class="table p-2 m-2">
    <tr class="border">
        <td>#</td>
        <td>Name </td>
        <td>Email</td>
        <td>Added on </td>
    </tr>

    @foreach ($customers as $customer)

        <tr class="border">
            <td>{{  $customer->id }}</td>
            <td>{{  $customer->name }} </td>
            <td>{{  $customer->email  }}</td>
            <td>{{  $customer->created_at }} </td>
        </tr>

    @endforeach



@endsection
