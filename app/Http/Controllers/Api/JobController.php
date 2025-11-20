<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobRequest;
use App\Http\Resources\JobResource;
use App\Models\Company;
use App\Models\Job;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $jobs = Job::query()
            ->where('creator_id', $request->user()->id)
            ->with('company')
            ->latest()
            ->paginate(10);

        return JobResource::collection($jobs);
    }

    public function showPrivate(Job $job): JobResource
    {
        $this->ensureJobOwner($job);

        return new JobResource($job->load(['company', 'applications.user']));
    }

    public function store(JobRequest $request): JsonResponse
    {
        $data = $request->validated();

        $this->ensureCompanyOwner($data['company_id'], $request->user()->id);

        $job = Job::create(array_merge(
            $data,
            [
                'creator_id' => $request->user()->id,
                'slug' => Str::slug($data['title']) . '-' . Str::random(8),
            ],
        ));

        return response()->json([
            'job' => new JobResource($job->load('company')),
            'message' => 'Job created',
        ], 201);
    }

    public function update(JobRequest $request, Job $job): JsonResponse
    {
        $this->ensureJobOwner($job);

        $data = $request->validated();

        if (isset($data['company_id'])) {
            $this->ensureCompanyOwner($data['company_id'], $request->user()->id);
        }

        $job->update($data);

        return response()->json([
            'job' => new JobResource($job->fresh('company')),
            'message' => 'Job updated',
        ]);
    }

    public function destroy(Job $job): JsonResponse
    {
        $this->ensureJobOwner($job);

        $job->delete();

        return response()->json(['message' => 'Job deleted']);
    }

    public function publish(Job $job): JsonResponse
    {
        $this->ensureJobOwner($job);

        $job->update([
            'status' => 'published',
            'published_at' => now(),
            'expires_at' => $job->expires_at ?? now()->addMonth(),
        ]);

        return response()->json([
            'job' => new JobResource($job),
            'message' => 'Job published',
        ]);
    }

    public function feature(Job $job): JsonResponse
    {
        $this->ensureJobOwner($job);

        $job->update([
            'featured_until' => now()->addWeeks(2),
        ]);

        return response()->json([
            'job' => new JobResource($job),
            'message' => 'Job featured for two weeks',
        ]);
    }

    public function archive(Job $job): JsonResponse
    {
        $this->ensureJobOwner($job);

        $job->update([
            'status' => 'archived',
        ]);

        return response()->json([
            'job' => new JobResource($job),
            'message' => 'Job archived',
        ]);
    }

    public function publicIndex(Request $request)
    {
        $query = Job::query()
            ->published()
            ->with('company')
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now());
            });

        if ($search = $request->string('search')->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        foreach ([
            'experience_level',
            'location_type',
            'country',
            'contract_type',
            'vue_version',
            'nuxt_version',
        ] as $filter) {
            if ($value = $request->string($filter)->toString()) {
                $query->where($filter, $value);
            }
        }

        if ($request->boolean('requires_typescript')) {
            $query->where('requires_typescript', true);
        }

        $skills = $request->input('skills');
        if (is_array($skills) && filled($skills)) {
            foreach ($skills as $skill) {
                $query->whereJsonContains('skills', $skill);
            }
        } elseif (is_string($skills) && strlen($skills)) {
            $query->whereJsonContains('skills', $skills);
        }

        if ($request->filled('salary_min')) {
            $query->where('salary_min', '>=', (int) $request->input('salary_min'));
        }

        if ($request->filled('salary_max')) {
            $query->where('salary_max', '<=', (int) $request->input('salary_max'));
        }

        $jobs = $query->latest('featured_until')
            ->latest()
            ->paginate(12);

        return JobResource::collection($jobs);
    }

    public function showPublic(Job $job): JobResource
    {
        abort_unless($job->status === 'published', 404);

        return new JobResource($job->load('company'));
    }

    protected function ensureCompanyOwner(int $companyId, int $userId): void
    {
        $company = Company::query()
            ->where('id', $companyId)
            ->where('owner_id', $userId)
            ->first();

        abort_if(! $company, 403, 'You can only manage your companies');
    }

    protected function ensureJobOwner(Job $job): void
    {
        abort_unless($job->creator_id === request()->user()->id, 403);
    }
}

