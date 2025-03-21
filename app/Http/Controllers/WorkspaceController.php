<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use App\Http\Requests\StoreWorkspaceRequest;
use App\Http\Requests\UpdateWorkspaceRequest;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Arr;

class WorkspaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $workspaces = Workspace::latest()->get();
        return response()->json($workspaces);
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

    public function massDestroy(HttpRequest $request)
    {
        // This method retrieves the value for the key 'ids' from the request's input data. Can name it whatever from postman
        $ids = $request->input('ids', []);
        // filters the records in the workspaces table so that only those records whose id is in the provided $ids array are returned.
        //  It doesn't retrieve only the id column; by default, it retrieves all columns for each matching record.

        // The empty() function in PHP checks whether a variable is empty. It returns true if the variable:
        // [] is going to be empty array so will return true, "" s empty 0 is also empty
        if (!is_array($ids) || empty($ids)) {
            return response()->json(['error' => 'Invalid input. Provide an array of workspace IDs.'], 422);
        }

        // If you only need specific columns, you could chain a select method, like this:
        // This would return only the id column for the matching records.

        // Workspace::whereIn('id', $ids)->select('id')->get();

        // but we need workspace other columns for other things
        // Retrieve workspaces by provided IDs.

        // Optionally, you might want to ensure the workspaces belong to the current user.
        // For example:
        $workspaces = $request->user()->workspaces()->whereIn('id', $ids)->get();

        // Check authorization for each workspace.
        foreach ($workspaces as $workspace) {
            Gate::authorize('modify', $workspace);
        }
        // try catch outside to fail operation and stop 

        try {
            // Delete each workspace individually so that model events (e.g., observer methods) are fired.
            foreach ($workspaces as $workspace) {
                $workspace->delete();
            }

            return response()->json(['message' => 'Selected workspaces have been deleted.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        // if we have something like below then we delete as many as possible and display list of errors if any


        //         $errors = [];
        // foreach ($workspaces as $workspace) {
        //     try {
        //         $workspace->delete();
        //     } catch (\Exception $e) {
        //         // Log error or collect errors for this specific workspace.
        //         $errors[] = "Error deleting workspace with id {$workspace->id}: " . $e->getMessage();
        //     }
        // }

        // if (!empty($errors)) {
        //     return response()->json(['error' => $errors], 400);
        // }

        // return response()->json(['message' => 'Selected workspaces have been deleted.']);
    }

    public function updateSetting(HttpRequest $request, Workspace $workspace)
{
    // Validate the request
    $request->validate([
        'key'   => 'required|string',  // e.g., "security_settings.whitelisted_countries"
        'value' => 'required|boolean', // true or false
    ]);

    // Retrieve key and value from the request
    $key = $request->input('key');
    $value = $request->input('value');

    // Get current settings (or default to an empty array)
    $settings = $workspace->setting ?? [];

    // Use Arr::set to update the setting by dot notation.
    // For example, if key is "security_settings.whitelisted_countries", it updates that nested value.
    Arr::set($settings, $key, $value);
    // The Arr::set helper is provided by Laravel (found in Illuminate\Support\Arr). 
    // It allows you to update a nested array value using dot notation. The method signature is roughly:
    // Save the updated settings back to the workspace
    $workspace->setting = $settings;
    $workspace->save();

    return response()->json([
        'message'   => 'Workspace setting updated successfully',
        'workspace' => $workspace,
    ]);
}
}
