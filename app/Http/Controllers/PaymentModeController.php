<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PaymentModeRequest;
use App\PaymentMode;

class PaymentModeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.payment-mode');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentModeRequest $request)
    {
        $paymentModeStoreStatus = false;
        $providerPaymentModeData = $request->all();
        if (PaymentMode::create([
            'name' => $providerPaymentModeData['name'],
            'user_id' => auth()->user()->id,
            'created_at' => date('Y-m-d H:i:s')
        ])) {
            $paymentModeStoreStatus = true;
        }
        return \response()->json($paymentModeStoreStatus);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PaymentModeRequest $request, $id)
    {
        $paymentModeUpdateStatus = false;
        $providedPaymentModeInfo = $request->all();
        $paymentMode = PaymentMode::find($id);

        $paymentMode->name = $providedPaymentModeInfo['name'];

        if ($paymentMode->save()) {
            $paymentModeUpdateStatus = true;
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
        $paymentMode = PaymentMode::find($id);

        if ($paymentMode->delete()) {
            return response()->json([
                'success' => true
            ]);
        }
    }


    public function changeStatus($id)
    {
        $paymentMode = PaymentMode::find($id);
        $paymentMode->status = $paymentMode->status == 1 ? 0 : 1;
        
        if ($paymentMode->save()) {
            $paymentModeStatusUpdateStatus = true;
        }

        return response()->json([
            'paymentmode' => $paymentMode,
            'success' => $paymentModeStatusUpdateStatus
        ]);
    }

    public function fetchAllPaymentModes(Request $request)
    {
        // get all params from request url
        $searchParams = $request->all();

        $paymentModes = PaymentMode::where([
            'user_id' => auth()->user()->id
        ]);

        // if search param has product_id
        if ($searchParams['name'] != null) {
            $paymentModes = $paymentModes->where('name', 'like', $searchParams['name'].'%');
        }

        $paymentModes = $paymentModes->paginate(10);
        
        return response()->json($paymentModes);
    }


    public function fetchAllActivePaymentModes()
    {
        $paymentModes = PaymentMode::where([
            'user_id' => auth()->user()->id
        ])->get();
        
        return response()->json($paymentModes);
    }
}
