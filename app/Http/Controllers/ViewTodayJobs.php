<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FJOHeader;


class ViewTodayJobs extends Controller
{
    public function index(Request $response)
    {
		$records = FJOHeader::whereDate("svod_date",now()->format("Y-m-d "))
		->orderBy("svod_date", "asc")->get();
		
		//dd($records,now());
		
        return view("vtj.index", compact("records"));
		
    }	
}
