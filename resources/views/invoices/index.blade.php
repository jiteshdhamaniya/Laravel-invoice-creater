@extends('layouts.app')

@section('content')

    <table class="table p-2 m-2">
        <tr class="border">
            <td>#</td>
            <td>Bill to </td>
            <td>Invoice Date</td>
            <td>Created on </td>
            <td> Action </td>
        </tr>

        @foreach ($invoices as $invoice)

            <tr class="border">
                <td>{{  $invoice['meta']['invoiceno'] }}</td>
                <td>{{  $invoice['meta']['billto'] }} </td>
                <td>{{  $invoice['meta']['date']  }}</td>
                <td>{{ $invoice->created_at }} </td>
                <td> <a href="{{ route('invoices.show', $invoice->id ) }}"> View </a> </td>
            </tr>

        @endforeach


    </table>


@endsection
