<?php

namespace App\Http\Controllers\ManageTimeframeAndQuota;

use App\Http\Controllers\Controller;
use App\Models\LecturerQuota;
use Illuminate\Http\Request;

class QuotaController extends Controller
{
    public function displayLecturerQuota()
    {
        // Retrieve lecturer data with program and quotas
        $lecturers = LecturerQuota::with(['lecturer.program'])
            ->get();

        return view('ManageTimeframeAndQuota.lecturer-quota-list', compact('lecturers'));
    }
}
