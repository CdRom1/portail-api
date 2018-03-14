<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ReservationType;
//use App\Http\Requests\ReservationTypeRequest;

class ReservationTypeController extends Controller
{
    public function __construct() {
        // $this->middleware('auth:api');
        // TODO: Role admin.
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reservationTypes = ReservationType::get();
        return response()->json($reservationTypes, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $reservationType = ReservationType::create($request->input());

        if ($reservationType)
            return response()->json($reservationType, 200);
        else
            return response()->json(["message" => "Impossible de créer le type de réservation"], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $reservationType = ReservationType::find($id);
        if ($reservationType)
            return response()->json($reservationType, 200);
        else
            return response()->json(["message" => "Impossible de trouver le type de réservation"], 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $reservationType = ReservationType::find($id);

        $reservationType = ReservationType::update($request->input());
        if ($reservationType)
            return response()->json($reservationType, 200);
        else
            return response()->json(["message" => "Impossible de modifier le type de réservation"], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
