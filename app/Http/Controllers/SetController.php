<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SetController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Set $set
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Set $set)
    {
        //
    }

}
