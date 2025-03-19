<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use App\Http\Requests\StoreWorkspaceRequest;
use App\Http\Requests\UpdateWorkspaceRequest;
use Illuminate\Support\Facades\Gate;

class WorkspaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Workspace::latest();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWorkspaceRequest $request)
    {
        $data = $request->validated();
        $workspace = $request->user()->workspaces()->create($data);


        return response()->json($workspace, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Workspace $workspace)
    {
        return $workspace;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWorkspaceRequest $request, Workspace $workspace)
    {
        Gate::authorize('modify', $workspace);

        $workspace->update($request->validated());

        return $workspace;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Workspace $workspace)
    {
        Gate::authorize('modify', $workspace);

        try {
            $workspace->delete();
            return response()->json(['message' => "Workspace was deleted"]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
