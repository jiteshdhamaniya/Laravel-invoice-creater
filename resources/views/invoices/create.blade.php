@extends('layouts.app')

@inject('customers', 'App\Customer')

@section('content')

<script>
	$( function() {
		$( ".datepicker" ).datepicker();
	} );
	</script>


    <script>


        var j = 0;

        function AddLineItems(){

            var element = jQuery("#firstLine");
            var elementLines = jQuery("#elementLines");

            var html  = element.html();

            j = j+1;
            inputName = "lineItems[" + j + "]"
            html = html.replace('lineItems[0]', inputName )
            elementLines.append(html);

            $("#elementLines input").change();

        }

        $( document ).ready(function() {

           jQuery("#elementLines").on('click', '.Remove', function(e){
               e.preventDefault();
               console.log('removed');
               $(this).parent().parent().remove();

               $("#elementLines input").change();

           })

        jQuery("#elementLines").on('change', 'input', function(){

            var quantity = $(".quantity");
            var rate = $(".rate");
            var amount = $(".amount");

            var totalAmount = 0;
            var current_amount = 0;

            for (let index = 0; index < quantity.length; index++ ) {

                quantity_ = parseInt($(quantity[index]).val());
                rate_ = parseInt($(rate[index]).val());

                current_amount = isNaN(quantity_*rate_) ? 0 : quantity_*rate_ ;

                // set
                $(amount[index]).val( current_amount );

                totalAmount = totalAmount + parseInt( current_amount );

            }

            $(".totalAmount").val(totalAmount);
            $(".totalAmount").text(totalAmount);

        })


        })// ready function

        // function submitHandle(e){
        //         e.preventDefault();
        //        console.log( document.forms[0].serialize() );

        // }

        // $( document ).ready(function() {

        // $( "form" ).on( "submit", function( event ) {
        //     event.preventDefault();
        //     console.log( $( this ).serialize() );
        //     });

        // })// ready function

        </script>


    <body>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                @include('components.alert', ['slot'=>$error ])
            @endforeach
        @endif

  <form action="{{ route('create.invoice') }}" method="POST">
    @csrf
    <div class="container p-10">

        <div class="flex mb-4 p-5">

            <div class="border p-2 w-3/4 border">

                <div class="flex mb-4">
                <div class="w-3/4">

                <div class="flex">
                        <div class="w-1/2">

                            <label for="billto"></label> <br/>

                            <select
                            id="billto"
                            name="billto"
                            class="p-2 border rounded"
                            >
                                @foreach ($customers::all() as $customer)
                                     <option value="{{ $customer->email }}">{{ $customer->name }}({{ $customer->email  }})</option>
                                @endforeach

                            </select>
                                <br/>
                            <a
                            class="border p-1 pr-2 pl-2 m-1 inline-block text-black
                             no-underline
                             rounded
                             hover:no-underline
                             hover:bg-blue-500
                             hover:text-white
                            "
                            href="{{ route('customers.create') }}">
                                Add Customer
                            </a>

                        </div>
                </div>

                </div>
                <div class="w-1/4">

                     <label for="date"> </label>
                     <input class="m-2 border p-2 rounded datepicker"
                        placeholder="Date"
                        type="text"
                        name="date"
                        id="date"
                        />

                    <label for="invoiceno" > </label>
                    <input class="m-2 border p-2 rounded"
                    placeholder="invoiceno"
                    type="text"
                    name="invoiceno"
                    id="invoiceno"
                    />

                    <label for="duedate" > </label>
                    <input class="m-2 p-2 rounded border datepicker"
                    placeholder="duedate"
                    type="text"
                    name="duedate"
                    id="duedate"
                    />

                </div>
                </div>


                <div class="block">
                    <div class="block" id="elementLines">

                    <div class="flex mb-4">
                        <div class="w-1/4 border">Item</div>
                        <div class="w-1/2 border">Quantity</div>
                        <div class="w-1/2 border">Rate</div>
                        <div class="w-1/2 border">Amount</div>
                    </div>

                    <div id="firstLine">
                            <div class="flex mb-4"
                            ><div class="w-3/4 border">
                                    <input
                                    class="p-1  w-full"
                                    type="text"
                                    name="lineItems[0][description]"
                                    placeholder="Description"
                                    onChange={this.handleRowItems}
                                     />
                                </div>

                                <div class="w-1/4 border">
                                    <input
                                        class="p-1 quantity"
                                        name="lineItems[0][quantity]"
                                        min="0"
                                        type="number"
                                        value="1"
                                        placeholder="Quantity"
                                        onChange={this.handleRowItems}
                                        />
                                    </div>
                                <div class="w-1/4 border">
                                    <input
                                    class="p-1 rate"
                                    name="lineItems[0][rate]"
                                    type="number"
                                    min="1"
                                    value="1"
                                    placeholder="rate"
                                    onChange={this.handleRowItems}
                                    /></div>
                                <div class="w-1/4 border">
                                    <input
                                    class="p-1 amount"
                                    name="lineItems[0][amount]"
                                    min="1"
                                    value="1"
                                    type="number"
                                    placeholder="amount"
                                 /></div>

                                 <div class="w-1/4 border">
                                    <a class="Remove">Remove</a>
                                 </div>
                            </div>

                    </div>

                </div>
                   <a
                 class="bg-blue-500 p-2 m-2 text-white cursor-pointer"
                   onclick="AddLineItems()"
                   > Line items </a>

                </div>

                <div class=" clearfix mt-20 "></div>

                <div class="flex mb-4">
                        <div class="w-3/4 mr-2">

                            <textarea name="notes"
                            onChange={this.handleInputChange}
                            placeholder="Notes - any relevant information not already covered" class="border w-3/4"></textarea>

                            <textarea name="terms"
                            onChange={this.handleInputChange}
                            placeholder="Terms and conditions - late fees, payment methods, delivery schedule" class="border w-3/4"></textarea>

                            </div>

                        <div class="w-1/4">
                            <div class="flex">
                                <div class="w-2/3 border p-2 ">
                                    Total
                                </div>
                                <div class="w-1/3 border p-2 ">
                                    $<input type="hidden" class="totalAmount" name="totalAmount" value="10">
                                    <div class="totalAmount"></div>
                                </div>
                            </div>
                        </div>

                    </div>

              </div>

                    <div class="w-1/4 border ml-1 p-5">
                        <input class=" bg-blue-500 p-3 m-2 shadow-lg rounded text-white " type="submit" value="Download Invoice">
                    </div>
                </div>
            </div>

        </form>

 @endsection
