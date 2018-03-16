<?php

namespace App\Http\Controllers;

use App\Models\AssoType;
use App\Http\Requests\AssoTypeRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AssoTypeController extends Controller
{
	public function __construct() {
        // $this->middleware('auth:api', 'role:admin');
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$types = AssoType::get();
		return response()->json($types, 200);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(AssoTypeRequest $request) {
		$type = AssoType::create($request->input());
		if ($type)
			return response()->json($type, 200);
		else
			return response()->json(["message" => "Impossible de créer le type"], 500);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$type = AssoType::find($id);
        if ($type)
            return response()->json($type, 200);
        else
            return response()->json(["message" => "Impossible de trouver le type d'asso"], 404);
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
		$type = AssoType::find($id);

        $type = AssoType::update($request->input());
        if ($type)
            return response()->json($type, 200);
        else
            return response()->json(["message" => "Impossible de modifier le type"], 500);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		// Voir ce qu'il faut faire, a priori pas de destroy.
	}
}
