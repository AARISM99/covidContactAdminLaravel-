<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class DeviceApiController extends Controller
{

    public function save(Request $request)
    {
        Log::info("Device info");
        Log::info($request);
        $devise = Device::where("udid", $request->udid)->firstOrNew();
        // if (empty($devise->key)) {
        //     $devise->key = Str::uuid();
        // }
        $devise->udid = $request->udid;
        $devise->token = $request->token;
        $devise->covid = $request->covid == true ? 1 : 0;
        $res = $devise->save();

        Log::info($res);
        return Device::where("udid", $request->udid)->first();
    }
}
