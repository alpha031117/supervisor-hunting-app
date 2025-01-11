<?php

namespace App\Http\Controllers\ManageTitle;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\StudentApplication;
use App\Models\LecturerQuota;
use App\Models\Notification;
use Illuminate\Http\Request;

class TitleController extends Controller
{
    public function accessdb()
    {
        $proposaldata = Proposal::all();
        return view('ApplyTitle.ProposalList', compact('proposaldata'))->name('ProposalList');
    }
}
