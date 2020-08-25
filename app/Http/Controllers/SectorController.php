<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SectorRequest;
use App\Sector;

class SectorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.sector');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SectorRequest $request)
    {
        $sectorStoreStatus = false;
        $providerSectorData = $request->all();
        if (Sector::create([
            'name' => $providerSectorData['name'],
            'user_id' => auth()->user()->id,
            'created_at' => date('Y-m-d H:i:s')
        ])) {
            $sectorStoreStatus = true;
        }
        return \response()->json($sectorStoreStatus);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SectorRequest $request, $id)
    {
        $sectorUpdateStatus = false;
        $providedSectorInfo = $request->all();
        $sector = Sector::find($id);

        $sector->name = $providedSectorInfo['name'];

        if ($sector->save()) {
            $sectorUpdateStatus = true;
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
        $sector = Sector::find($id);

        if ($sector->delete()) {
            return response()->json([
                'success' => true
            ]);
        }
    }

    public function changeStatus($id)
    {
        $sector = Sector::find($id);
        $sector->status = $sector->status == 1 ? 0 : 1;
        
        if ($sector->save()) {
            $sectorStatusUpdateStatus = true;
        }

        return response()->json([
            'sector' => $sector,
            'success' => $sectorStatusUpdateStatus
        ]);
    }

    public function fetchAllSectors(Request $request)
    {
        // get all params from request url
        $searchParams = $request->all();
        $start_date = $searchParams['start_date'] .' 00:00:00';
        $end_date = $searchParams['end_date'] .' 23:59:59';

        $sectors = Sector::where([
            'user_id' => auth()->user()->id
        ]);

        // if search param has product_id
        if ($searchParams['name'] != null) {
            $sectors = $sectors->where('name', 'like', $searchParams['name'].'%');
        }

        $sectors = $sectors->whereBetween('created_at', [$start_date, $end_date])->paginate(10);
        
        return response()->json($sectors);
    }

    public function fetchAllActiveSectors()
    {
        $sectors = Sector::where([
            'user_id' => auth()->user()->id
        ])->get();
        
        return response()->json($sectors);
    }
}
