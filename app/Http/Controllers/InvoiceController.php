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
            'currency'=>'required'
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

    /**
     * Make PDF
     */

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
                font-family: Arial, Helvetica, sans-serif;
            }

            .text-right{
                text-align: right;
            }

            .w-50{
                width: 50%;
            }

            td{
                padding:5px;
                margin:0px;
            }

            .grey{
                background-color: #ddd;
            }

            .border{
                border: 1px solid #000;
            }

            .itemTable{
                margin-top:20px;
            }

            .pt-100{
                padding-top: 100px;;
            }

            </style>

        <table width='100%' cellspacing='0' cellpadding='0'>
        <tr>
            <td class='w-50'>
                <img src='https://media-exp1.licdn.com/dms/image/C4D0BAQEG1o0rnIFR0Q/company-logo_200_200/0?e=2159024400&v=beta&t=qCoHhxFxGz9RZilRTF3oDMXs6TZLvmfDHUqu86ihoRs'
                        border='0'
                        height='150'
                        width='150'
                        />
            </td>
            <td class='w-50 text-right'>
                <h1>INVOICE</h1>
                #". $data['invoiceno']. "

            </td>
        </tr>

        <tr>
            <td>
                <strong> Bill To: ". $data['billto'] ." </strong>
            </td>

            <td>
                <table align='right' width='70%' class='text-right' cellspacing='0' cellpadding='0'>
                    <tr>
                        <td>Date</td>
                        <td> ". $data['date']. "</td>
                    </tr>
                    <tr>
                        <td>Due Date</td>
                        <td> ". $data['duedate']. "</td>
                    </tr>
                    <tr class='grey'>
                        <td>Balance Due</td>
                        <td> ".$data['totalAmount']."</td>
                    </tr>
                </table>

            </td>

        </tr>

        <tr>
            <td colspan='2'>

            <br/><br/><br/><br/><br/>

                <table class='itemTable' width='100%' cellspacing='1'>
                    <tr class='grey'>
                        <td>Name</td>
                        <td>Qty.</td>
                        <td>Rate</td>
                        <td>Amount</td>
                    </tr>
                     ". $itemRows . "
                </table>
            </td>
        </tr>

        <tr>
            <td class='pt-100'>
            <h3>
            Notes: </h3>

           <p>
                ". $data['notes'] . "
           </p>

           <h3>
           Terms: </h3>

          <p>
               ". $data['terms'] . "
          </p>

            </td>

            <td style='vertical-align: bottom;' class='pt-100'>

                <table class='text-right' align='right' width='70%' cellspacing='0' cellpadding='0'>
                    <tr class='grey'>
                        <td>Total</td>
                        <td>".$data['currency'].$data['totalAmount']."</td>
                    </tr>
                </table>

            </td>

        </tr>


    </table>";


        return $table;

    }

}
