<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\PDF;

use Illuminate\Http\Request;


use App\Invoice;
use App\Customer;

class InvoiceController extends Controller
{

    public function index(){

        $user = auth()->user();
        $invoices = $user->invoices;

        return view('invoices.index',compact('invoices'));

    }

    public function show(Invoice $invoice){

        $data = collect($invoice->meta);

        $table = $this->makePdf($data);

        $pdf = app()->make('dompdf.wrapper');
        $pdf = $pdf->
                loadHTML($table)
                ->setWarnings(false);

        return $pdf->stream($data['invoiceno'].".pdf");

    }

    function create(){

        $user = auth()->user();

        // data
        $data = request()->validate([
            'billto'=>'required',
            'invoiceno'=>'required',
            'date'=>'required',
            'duedate'=>'required',
            'lineItems'=>'required',
            'totalAmount'=>'required',
            'notes'=>'required',
            'terms'=>'required',
        ]);


        $data['customer_id'] = (Customer::where('email', $data['billto'])->get()->first()->id) ?? redirect()->back();

        $invoice = $user->invoices()->create([
            'meta'=> $data
        ]);

        $data = collect($data);

        $table = $this->makePdf($data);

        $pdf = app()->make('dompdf.wrapper');
        $pdf = $pdf->
                loadHTML($table)
                ->setWarnings(false);

        return $pdf->download($data['invoiceno'].".pdf");

    }

    private function makePdf($data){

        $itemRows = "";

        foreach ($data['lineItems'] as $item) {

            $description = isset($item['description']) ? $item['description'] : "";
            $quantity = isset($item['quantity']) ? $item['quantity'] : 0;
            $rate = isset($item['rate']) ? $item['rate'] : 0;
            $amount = isset($item['amount']) ? $item['amount'] : 0;

            $itemRows .= "
                <tr>
                    <td>" . $description . "</td>
                    <td>" . $quantity . "</td>
                    <td>" . $rate . "</td>
                    <td>" . $amount . "</td>
                </tr>";

        }

        $table = "
            <style>
                * {
                    font-family:'Arial, Helvetica, sans-serif';
                }

                table,tr{
                    width:100%;
                    padding:50px 0px;
                }

                td{
                }

                .grey{
                    background:grey;
                }

            </style>

        <table width='100%' border='0'  cellspacing='0' cellpadding='5'>
            <tr>
                <td colspan='2'>To:". $data['billto'] ."</td>
                <td colspan='2'>
                    <table>
                        <tr>
                            <td>Due Date:". $data['duedate']. "</td>
                        </tr>
                        <tr>
                            <td>Invoice No.: ". $data['invoiceno']. "</td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class='grey'>
                <td>item</td>
                <td>Quantity</td>
                <td>Rate</td>
                <td>Amount</td>
            </tr>

            ". $itemRows . "

           <tr>
               <td colspan='2'>
                    <table>
                        <tr>
                            <td>Notes:  ". $data['notes'] . " </td>
                        </tr>
                        <tr>
                            <td>Terms  ". $data['terms'] . "</td>
                        </tr>
                    </table>

               </td>
               <td colspan='2'>
                        <table>
                            <tr>
                                <td>Amount</td>
                                <td>".$data['totalAmount']."</td>
                            </tr>
                            <tr>
                                <td>Discount</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Total Amount</td>
                                <td>".$data['totalAmount']."</td>
                            </tr>
                        </table>
               </td>
           </tr>

        </table>";

        return $table;

    }

}
