<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $companies = $request->user()
            ->companies()
            ->withCount('jobs')
            ->latest()
            ->paginate(10);

        return CompanyResource::collection($companies);
    }

    public function store(CompanyRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['owner_id'] = $request->user()->id;

        if (! isset($data['slug'])) {
            $data['slug'] = Str::slug($data['name']) . '-' . Str::random(6);
        }

        $company = Company::create($data);

        Cache::flush();

        return response()->json([
            'company' => new CompanyResource($company),
            'message' => 'Company created',
        ], 201);
    }

    public function show(Company $company): CompanyResource
    {
        $this->ensureOwner($company);

        return new CompanyResource($company->loadCount('jobs'));
    }

    public function update(CompanyRequest $request, Company $company): JsonResponse
    {
        $this->ensureOwner($company);

        $company->update($request->validated());

        Cache::flush();

        return response()->json([
            'company' => new CompanyResource($company),
            'message' => 'Company updated',
        ]);
    }

    public function destroy(Company $company): JsonResponse
    {
        $this->ensureOwner($company);

        $company->delete();

        Cache::flush();

        return response()->json(['message' => 'Company deleted']);
    }

    public function showPublic(Company $company): CompanyResource
    {
        abort_unless($company->is_public, 404);

        return Cache::remember("companies:show:{$company->id}", 300, function () use ($company) {
            return new CompanyResource($company->loadCount('jobs'));
        });
    }

    protected function ensureOwner(Company $company): void
    {
        abort_unless($company->owner_id === request()->user()->id, 403);
    }
}

