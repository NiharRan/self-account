<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ServiceRequest;
use App\Http\Requests\ServiceUpdateRequest;
use App\Service;
use App\TransactionType;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.service');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ServiceRequest $request)
    {
        $serviceStoreStatus = false;
        $providerServiceData = $request->all();
        $amount = $providerServiceData['amount'];
        $paid = $providerServiceData['paid'];
        $due = $amount - $paid;
        if ($service = Service::create([
            'title' => $providerServiceData['title'],
            'amount' => $amount,
            'paid' => $paid,
            'due' => $due,
            'sector_id' => $providerServiceData['sector_id'],
            'payment_mode_id' => $providerServiceData['payment_mode_id'],
            'user_id' => auth()->user()->id,
            'serve_at' => date('Y-m-d', strtotime($providerServiceData['serve_at']))
        ])) {
            if ($service->transactions()->create([
                'amount' => $amount,
                'transaction_number' => $service->id,
                'transaction_type_id' => TransactionType::where('name', 'like', 'Service')->first()->id,
                'user_id' => auth()->user()->id,
                ])) {
                $serviceStoreStatus = true;
            }
        }
        return \response()->json($serviceStoreStatus);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ServiceUpdateRequest $request, $id)
    {
        $serviceUpdateStatus = false;
        $service = Service::find($id);
        $service->title = $request->title;
        $service->sector_id = $request->sector_id;
        if ($service->save()) {
            $serviceUpdateStatus = true;
        }

        return response()->json([
            'success' => $serviceUpdateStatus,
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
        $service = Service::with('transactions')->find($id);

        if ($service->forceDelete()) {
            return response()->json([
                'success' => true
            ]);
        }
    }

    public function changeStatus($id)
    {
        $service = Service::find($id);
        $service->status = $service->status == 1 ? 0 : 1;
        
        if ($service->save()) {
            $serviceStatusUpdateStatus = true;
        }

        return response()->json([
            'service' => $service,
            'success' => $serviceStatusUpdateStatus
        ]);
    }

    public function fetchAllServices(Request $request)
    {
        // get all params from request url
        $searchParams = $request->all();
        $start_date = $searchParams['start_date'] .' 00:00:00';
        $end_date = $searchParams['end_date'] .' 23:59:59';

        $services = Service::with(['sector', 'payment_mode', 'transactions'])->where([
            'user_id' => auth()->user()->id
        ]);

        // if search param has product_id
        if ($searchParams['name'] != null) {
            $services = $services->where('title', 'like', $searchParams['name'].'%');
        }

        $services = $services->whereBetween('serve_at', [$start_date, $end_date])->paginate(10);
        
        return response()->json($services);
    }
}
