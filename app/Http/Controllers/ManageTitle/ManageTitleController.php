<?php

namespace App\Http\Controllers\ManageTitle;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposal;

class ManageTitleController extends Controller
{
    public function DisplayProposalList()
    {
        $proposal = Proposal::all();
        dd($proposal);
        return view('ApplyTitle.ProposalList', compact('proposal'));
    }
}
