<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\PDF;

use Illuminate\Http\Request;

class InvoiceController extends Controller
{

    function create(){

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

        $data = collect($data);
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


        $pdf = app()->make('dompdf.wrapper');
        $pdf = $pdf->
                loadHTML($table)
                ->setWarnings(false);

        return $pdf->download($data['invoiceno'].".pdf");
    }

}
