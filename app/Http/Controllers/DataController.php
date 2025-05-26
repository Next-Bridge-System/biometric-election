<?php

namespace App\Http\Controllers;

use App\Bar;
use App\Division;
use Illuminate\Http\Request;
use App\District;
use App\Tehsil;

class DataController extends Controller
{
    public function getTehsilsByDistrict(Request $request)
    {
        $district = District::find($request->district_id);
        $tehsils = Tehsil::where('district_id', $request->district_id)->get();
        return ['district' => $district, 'tehsils' => $tehsils];
    }

    public function getDistrictByDivision(Request $request)
    {
        $division = Division::find($request->division_id);
        $district = District::where('division_id', $request->division_id)->get();
        return ['district' => $district, 'division' => $division];
    }

    public function getBarsByTehsil(Request $request)
    {
        $tehsil = Tehsil::find($request->tehsil_id);
        $bars = Bar::where('tehsil_id', $request->tehsil_id)->get();
        return ['bars' => $bars, 'tehsil' => $tehsil];
    }

    public function getBarsByDivision(Request $request)
    {
        if($request->has('division_id') && !empty($request->get('division_id'))){
            $division = Division::find($request->division_id);
            $districtIDs = District::where('division_id', $request->division_id)->pluck('id')->toArray();
            $tehsilIDs = Tehsil::select('id')->whereIn('district_id', $districtIDs)->pluck('id')->toArray();
            $bars = Bar::whereIn('tehsil_id', $tehsilIDs)->get();
        }else{
            $division = [];
            $bars = Bar::orderBy('name','asc')->get();
        }
        return ['bars' => $bars, 'division' => $division];
    }
}
