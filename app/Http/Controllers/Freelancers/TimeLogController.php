<?php
namespace App\Http\Controllers\Freelancers;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\TimeLog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TimeLogController extends Controller
{
    public function index(Request $request)
    {
        $query = TimeLog::where('user_id', Auth::id())->with('project');

        // Filter
        if ($request->has('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->has('client_id')) {
            $query->whereHas('project', function ($q) use ($request) {
                $q->where('client_id', $request->client_id);
            });
        }

        if ($request->has('start_date')) {
            $query->whereDate('start_time', '>=', $request->start_date);
        }
        if ($request->has('end_date')) {
            $query->whereDate('end_time', '<=', $request->end_date);
        }

        $timeLogs = $query->orderBy('start_time', 'desc')->get();

        // Calculate total hours
        $totalHours = $timeLogs->sum('hours');

        return response()->json([
            'status'      => true,
            'total_hours' => $totalHours,
            'time_logs'   => $timeLogs,
        ]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id'  => 'required|exists:projects,id',
            'start_time'  => 'required|date',
            'end_time'    => 'required|date|after_or_equal:start_time',
            'description' => 'nullable|string',
            'hours'       => 'nullable|numeric|min:0',
            'tag'         => 'nullable|in:billable,non-billable',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $project = Project::find($request->project_id);
        if (! Auth::user()->clients->pluck('id')->contains($project->client_id)) {
            return response()->json(['status' => false, 'message' => 'Unauthorized project'], 403);
        }

        // Calculate hours
        $hours = $request->hours;
        if (! $hours && $request->start_time && $request->end_time) {
            $start = Carbon::parse($request->start_time);
            $end   = Carbon::parse($request->end_time);
            $hours = $end->diffInMinutes($start) / 60;
        }

        $timeLog = TimeLog::create([
            'user_id'     => Auth::id(),
            'project_id'  => $request->project_id,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time,
            'description' => $request->description,
            'hours'       => round($hours, 2) ?? 0,
            'tag'         => $request->tag,
        ]);

        return response()->json(['status' => true, 'time_log' => $timeLog], 201);
    }
    public function show($id)
    {
        $timeLog = TimeLog::where('id', $id)->where('user_id', Auth::id())->first();

        if (! $timeLog) {
            return response()->json(['status' => false, 'message' => 'Time Log not found'], 404);
        }

        return response()->json(['status' => true, 'time_log' => $timeLog]);
    }

    public function update(Request $request, $id)
    {
        $timeLog = TimeLog::where('id', $id)->where('user_id', Auth::id())->first();

        if (! $timeLog) {
            return response()->json(['status' => false, 'message' => 'Time Log not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'start_time'  => 'nullable|date',
            'end_time'    => 'nullable|date|after_or_equal:start_time',
            'description' => 'nullable|string',
            'hours'       => 'nullable|numeric|min:0',
            'tag'         => 'nullable|in:billable,non-billable',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $timeLog->fill($request->only('start_time', 'end_time', 'description', 'hours', 'tag'));

        // Recalculate hours
        if (! $request->hours && $request->start_time && $request->end_time) {
            $start          = Carbon::parse($request->start_time);
            $end            = Carbon::parse($request->end_time);
            $timeLog->hours = round($end->diffInMinutes($start) / 60, 2);
        }

        $timeLog->save();

        return response()->json(['status' => true, 'message' => 'Time Log updated', 'time_log' => $timeLog]);
    }

    public function destroy($id)
    {
        $timeLog = TimeLog::where('id', $id)->where('user_id', Auth::id())->first();

        if (! $timeLog) {
            return response()->json(['status' => false, 'message' => 'Time Log not found'], 404);
        }

        $timeLog->delete();

        return response()->json(['status' => true, 'message' => 'Time Log deleted']);
    }

}
