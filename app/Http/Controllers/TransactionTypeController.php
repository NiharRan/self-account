<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TransactionTypeRequest;
use App\TransactionType;

class TransactionTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.transaction-type');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TransactionTypeRequest $request)
    {
        $transactionTypeStoreStatus = false;
        $providerTransactionTypeData = $request->all();
        if (TransactionType::create([
            'name' => $providerTransactionTypeData['name'],
            'user_id' => auth()->user()->id,
            'created_at' => date('Y-m-d H:i:s')
        ])) {
            $transactionTypeStoreStatus = true;
        }
        return \response()->json($transactionTypeStoreStatus);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TransactionTypeRequest $request, $id)
    {
        $transactionTypeUpdateStatus = false;
        $providedTransactionTypeInfo = $request->all();
        $transactionType = TransactionType::find($id);

        $transactionType->name = $providedTransactionTypeInfo['name'];

        if ($transactionType->save()) {
            $transactionTypeUpdateStatus = true;
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
        $transactionType = TransactionType::find($id);

        if ($transactionType->delete()) {
            return response()->json([
                'success' => true
            ]);
        }
    }

    public function changeStatus($id)
    {
        $transactionType = TransactionType::find($id);
        $transactionType->status = $transactionType->status == 1 ? 0 : 1;
        
        if ($transactionType->save()) {
            $transactionTypeStatusUpdateStatus = true;
        }

        return response()->json([
            'transactionType' => $transactionType,
            'success' => $transactionTypeStatusUpdateStatus
        ]);
    }

    public function fetchAllTransactionTypes(Request $request)
    {
        // get all params from request url
        $searchParams = $request->all();
        $start_date = $searchParams['start_date'] .' 00:00:00';
        $end_date = $searchParams['end_date'] .' 23:59:59';

        $transactionTypes = TransactionType::where([
            'user_id' => auth()->user()->id
        ]);

        // if search param has product_id
        if ($searchParams['name'] != null) {
            $transactionTypes = $transactionTypes->where('name', 'like', $searchParams['name'].'%');
        }

        $transactionTypes = $transactionTypes->whereBetween('created_at', [$start_date, $end_date])->paginate(10);
        
        return response()->json($transactionTypes);
    }
}
