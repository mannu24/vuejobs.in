<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobApplicationRequest;
use App\Http\Resources\JobApplicationResource;
use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index(Job $job)
    {
        $this->ensureJobOwner($job);

        $applications = $job->applications()
            ->with('user')
            ->latest()
            ->paginate(15);

        return JobApplicationResource::collection($applications);
    }

    public function store(JobApplicationRequest $request, Job $job): JsonResponse
    {
        abort_unless($job->status === 'published', 422, 'Job is not accepting applications');

        $existing = JobApplication::query()
            ->where('job_id', $job->id)
            ->where('user_id', $request->user()->id)
            ->exists();

        abort_if($existing, 422, 'You already applied to this job');

        $data = $request->validated();

        $application = JobApplication::create([
            'job_id' => $job->id,
            'user_id' => $request->user()->id,
            'cover_letter' => $data['cover_letter'] ?? null,
            'resume_url' => $data['resume_url'] ?? $request->user()->resume_url,
            'status' => 'applied',
        ]);

        return response()->json([
            'application' => new JobApplicationResource($application->load('user')),
            'message' => 'Application submitted',
        ], 201);
    }

    public function update(Request $request, JobApplication $application): JsonResponse
    {
        $application->loadMissing('job');
        $this->ensureJobOwner($application->job);

        $validated = $request->validate([
            'status' => ['required', 'in:applied,reviewed,shortlisted,rejected,hired'],
        ]);

        $status = $validated['status'];

        $timestamps = [
            'viewed_at' => now(),
            'shortlisted_at' => $status === 'shortlisted' ? now() : $application->shortlisted_at,
            'rejected_at' => $status === 'rejected' ? now() : $application->rejected_at,
            'hired_at' => $status === 'hired' ? now() : $application->hired_at,
        ];

        $application->update(array_merge(['status' => $status], $timestamps));

        return response()->json([
            'application' => new JobApplicationResource($application->fresh('user')),
            'message' => 'Application updated',
        ]);
    }

    protected function ensureJobOwner(Job $job): void
    {
        abort_unless($job->creator_id === request()->user()->id, 403);
    }
}

