<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TransactionRequest;
use App\Transaction;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.transaction');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TransactionRequest $request)
    {
        $transactionStoreStatus = false;
        $providerTransactionData = $request->all();
        if (Transaction::create([
            'name' => $providerTransactionData['name'],
            'user_id' => auth()->user()->id,
            'created_at' => date('Y-m-d H:i:s')
        ])) {
            $transactionStoreStatus = true;
        }
        return \response()->json($transactionStoreStatus);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TransactionRequest $request, $id)
    {
        $transactionUpdateStatus = false;
        $providedTransactionInfo = $request->all();
        $transaction = Transaction::find($id);

        $transaction->name = $providedTransactionInfo['name'];

        if ($transaction->save()) {
            $transactionUpdateStatus = true;
        }

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $transaction = Transaction::find($id);

        if ($transaction->delete()) {
            return response()->json([
                'success' => true
            ]);
        }
    }

    public function changeStatus($id)
    {
        $transaction = Transaction::find($id);
        $transaction->status = $transaction->status == 1 ? 0 : 1;
        
        if ($transaction->save()) {
            $transactionStatusUpdateStatus = true;
        }

        return response()->json([
            'transaction' => $transaction,
            'success' => $transactionStatusUpdateStatus
        ]);
    }

    public function fetchAllTransactions(Request $request)
    {
        // get all params from request url
        $searchParams = $request->all();
        $start_date = $searchParams['start_date'] .' 00:00:00';
        $end_date = $searchParams['end_date'] .' 23:59:59';

        $transactions = Transaction::where([
            'user_id' => auth()->user()->id
        ]);

        // if search param has product_id
        if ($searchParams['name'] != null) {
            $transactions = $transactions->where('name', 'like', $searchParams['name'].'%');
        }

        $transactions = $transactions->whereBetween('created_at', [$start_date, $end_date])->paginate(10);
        
        return response()->json($transactions);
    }
}
