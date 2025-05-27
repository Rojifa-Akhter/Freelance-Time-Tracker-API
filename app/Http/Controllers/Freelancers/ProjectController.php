<?php

namespace App\Http\Controllers\Freelancers;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::whereIn('client_id', Auth::user()->clients->pluck('id'))->get();
        return response()->json(['status' => true, 'projects' => $projects]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_id'  => 'required|exists:clients,id',
            'title'      => 'required|string|max:255',
            'description'=> 'nullable|string',
            'status'     => 'in:active,completed',
            'deadline'   => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $client = Auth::user()->clients()->find($request->client_id);
        if (!$client) {
            return response()->json(['status' => false, 'message' => 'Unauthorized client ID'], 403);
        }

        $project = $client->projects()->create($request->only('title', 'description', 'status', 'deadline'));

        return response()->json(['status' => true, 'project' => $project], 201);
    }

    public function show($id)
    {
        $project = Project::find($id);

        if (! $project || ! Auth::user()->clients->contains($project->client_id)) {
            return response()->json(['status' => false, 'message' => 'Project not found'], 404);
        }

        return response()->json(['status' => true, 'project' => $project]);
    }

    public function update(Request $request, $id)
    {
        $project = Project::find($id);

        if (! $project || ! Auth::user()->clients->contains($project->client_id)) {
            return response()->json(['status' => false, 'message' => 'Project not found'], 404);
        }

        $project->update($request->only('title', 'description', 'status', 'deadline'));

        return response()->json([
            'status' => true,
            'message' => 'Project Updated Successfully',
            'project' => $project]);
    }

    public function destroy($id)
    {
        $project = Project::find($id);

        if (! $project || ! Auth::user()->clients->contains($project->client_id)) {
            return response()->json(['status' => false, 'message' => 'Project not found'], 404);
        }

        $project->delete();

        return response()->json(['status' => true, 'message' => 'Project deleted']);
    }
}
