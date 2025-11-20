<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobAlertRequest;
use App\Http\Resources\JobAlertResource;
use App\Models\JobAlert;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobAlertController extends Controller
{
    public function index(Request $request)
    {
        $alerts = $request->user()->alerts()->latest()->get();

        return JobAlertResource::collection($alerts);
    }

    public function store(JobAlertRequest $request): JsonResponse
    {
        $alert = JobAlert::create([
            'user_id' => $request->user()->id,
            'filters' => $request->validated()['filters'],
            'frequency' => $request->input('frequency', 'daily'),
        ]);

        return response()->json([
            'alert' => new JobAlertResource($alert),
            'message' => 'Job alert saved',
        ], 201);
    }

    public function destroy(JobAlert $alert): JsonResponse
    {
        abort_unless($alert->user_id === request()->user()->id, 403);

        $alert->delete();

        return response()->json(['message' => 'Alert deleted']);
    }
}

