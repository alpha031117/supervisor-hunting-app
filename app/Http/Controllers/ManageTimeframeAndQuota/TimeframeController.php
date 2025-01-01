<?php

namespace App\Http\Controllers\ManageTimeframeAndQuota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TimeframeController extends Controller
{
    public function setTimeframe()
    {
        return view('ManageTimeframeAndQuota.set-timeframe');
    }

    public function editTimeframe()
    {
        // Return the edit view with the timeframe data
        return view('ManageTimeframeAndQuota.edit-timeframe');
    }
}
