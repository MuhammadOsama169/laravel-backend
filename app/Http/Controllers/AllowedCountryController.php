<?php

namespace App\Http\Controllers;

use App\Models\AllowedCountry;
use App\Http\Requests\StoreAllowedCountryRequest;
use App\Http\Requests\UpdateAllowedCountryRequest;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AllowedCountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Workspace $workspace)
    {
       $allowedCountries = $workspace->allowedCountries()->latest()->get();
       return response()->json($allowedCountries);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAllowedCountryRequest $request , Workspace $workspace)
    {
        $data = $request->validated();
        $allowedCountry = $workspace->allowedCountries()->create($data);

        return response()->json([
            "message"=>"Added",
            "data"    => [
                "allowed_countries" => $allowedCountry->allowed_countries,
                "id" => $allowedCountry->id,
                "ref_id"=> $workspace->ref_id,
            ]
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(AllowedCountry $allowedCountry)
    {
        return response()->json($allowedCountry);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAllowedCountryRequest $request, Workspace $workspace, AllowedCountry $allowedCountry)
    {
        Gate::authorize('modify', $allowedCountry);
    
        $allowedCountry->update($request->validated());
    
        return $allowedCountry;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AllowedCountry $allowedCountry,Workspace $workspace)
    {
        Gate::authorize('modify', $allowedCountry);

        try {
            $allowedCountry->delete();
        } catch (\Exception $e) {  
            return response()->json(["message" => "Error deleting record"], 400);
        }
        
        return response()->json(["message" => "Deleted"]);
    }
}
