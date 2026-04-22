@extends('admin.layouts.app')
@section('title', 'Import Jobs')

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <!-- Upload card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-medium"><i class="bi bi-upload me-1"></i> Upload Excel / CSV</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.jobs.import.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="file" class="form-label small fw-medium">Select file</label>
                        <input
                            id="file" name="file" type="file"
                            accept=".xlsx,.xls,.csv"
                            class="form-control @error('file') is-invalid @enderror"
                            required
                        >
                        @error('file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted">Accepts .xlsx, .xls, .csv — max 5MB</small>
                    </div>
                    <button type="submit" class="btn btn-vue">
                        <i class="bi bi-cloud-upload me-1"></i> Import Jobs
                    </button>
                </form>
            </div>
        </div>

        <!-- Import errors -->
        @if(session('import_errors'))
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0 fw-medium text-danger"><i class="bi bi-exclamation-triangle me-1"></i> Import Issues</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0 small">
                        @foreach(session('import_errors') as $error)
                            <li class="text-danger mb-1"><i class="bi bi-x-circle me-1"></i> {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Column reference -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-medium"><i class="bi bi-table me-1"></i> Column Reference</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Column</th>
                                <th>Required</th>
                                <th>Values / Notes</th>
                            </tr>
                        </thead>
                        <tbody class="small">
                            <tr><td class="ps-3"><code>title</code></td><td><span class="badge bg-danger-subtle text-danger">Yes</span></td><td>Job title</td></tr>
                            <tr><td class="ps-3"><code>company_name</code></td><td><span class="badge bg-danger-subtle text-danger">Yes</span></td><td>Company name</td></tr>
                            <tr><td class="ps-3"><code>department</code></td><td>No</td><td>e.g. Engineering, Product, Design</td></tr>
                            <tr><td class="ps-3"><code>location</code></td><td>No</td><td>City or region</td></tr>
                            <tr><td class="ps-3"><code>country</code></td><td>No</td><td>e.g. India, United States</td></tr>
                            <tr><td class="ps-3"><code>timezone</code></td><td>No</td><td>e.g. Asia/Kolkata, America/New_York</td></tr>
                            <tr><td class="ps-3"><code>location_type</code></td><td>No</td><td>remote, hybrid, on_site (default: remote)</td></tr>
                            <tr><td class="ps-3"><code>contract_type</code></td><td>No</td><td>full_time, part_time, contract, freelance, internship</td></tr>
                            <tr><td class="ps-3"><code>experience_level</code></td><td>No</td><td>junior, mid, senior, lead</td></tr>
                            <tr><td class="ps-3"><code>vue_version</code></td><td>No</td><td>2 or 3</td></tr>
                            <tr><td class="ps-3"><code>nuxt_version</code></td><td>No</td><td>2 or 3</td></tr>
                            <tr><td class="ps-3"><code>requires_typescript</code></td><td>No</td><td>yes / no (default: no)</td></tr>
                            <tr><td class="ps-3"><code>salary_min</code></td><td>No</td><td>Number (e.g. 1500000)</td></tr>
                            <tr><td class="ps-3"><code>salary_max</code></td><td>No</td><td>Number (e.g. 2500000)</td></tr>
                            <tr><td class="ps-3"><code>currency</code></td><td>No</td><td>INR, USD, EUR, GBP (default: USD)</td></tr>
                            <tr><td class="ps-3"><code>salary_interval</code></td><td>No</td><td>yearly, monthly, weekly, daily, hourly</td></tr>
                            <tr><td class="ps-3"><code>description</code></td><td>No</td><td>Job description (HTML or plain text)</td></tr>
                            <tr><td class="ps-3"><code>apply_url</code></td><td>No</td><td>External application URL</td></tr>
                            <tr><td class="ps-3"><code>skills</code></td><td>No</td><td>Comma separated: Vue.js, Nuxt, TypeScript</td></tr>
                            <tr><td class="ps-3"><code>benefits</code></td><td>No</td><td>Comma separated: Health Insurance, Remote Work</td></tr>
                            <tr><td class="ps-3"><code>source</code></td><td>No</td><td>e.g. linkedin, naukri, indeed (default: import)</td></tr>
                            <tr><td class="ps-3"><code>source_url</code></td><td>No</td><td>Original job listing URL</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-medium">Template</h6>
            </div>
            <div class="card-body">
                <p class="small text-muted mb-3">Download the Excel template with all 22 columns and 5 sample rows covering Vue.js, React, Nuxt, and full-stack roles.</p>
                <a href="{{ route('admin.jobs.import.template') }}" class="btn btn-vue btn-sm w-100">
                    <i class="bi bi-file-earmark-excel me-1"></i> Download Excel Template
                </a>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-medium">Tips</h6>
            </div>
            <div class="card-body small text-muted">
                <ul class="mb-0 ps-3">
                    <li class="mb-2">First row must be column headers (exact names from template)</li>
                    <li class="mb-2">Only <code>title</code> and <code>company_name</code> are required — rest is optional</li>
                    <li class="mb-2">Duplicates are auto-skipped (matched by title + company + location)</li>
                    <li class="mb-2">Jobs are published immediately with 30-day expiry</li>
                    <li class="mb-2">Skills and benefits should be comma-separated in one cell</li>
                    <li>You can delete the sample rows and fill in your own data</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
