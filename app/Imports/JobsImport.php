<?php

namespace App\Imports;

use App\Models\Job;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class JobsImport implements ToCollection, WithHeadingRow
{
    public int $imported = 0;
    public int $skipped = 0;
    public array $errors = [];

    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            $rowNum = $index + 2;

            try {
                $title = trim($row['title'] ?? '');
                $companyName = trim($row['company_name'] ?? '');

                if (! $title || ! $companyName) {
                    $this->skipped++;
                    $this->errors[] = "Row {$rowNum}: Missing title or company_name";
                    continue;
                }

                // Dedup
                $hash = md5(strtolower($title) . '|' . strtolower($companyName) . '|' . strtolower(trim($row['location'] ?? '')));
                if (Job::where('hash', $hash)->exists()) {
                    $this->skipped++;
                    continue;
                }

                // Sanitize description
                $description = $row['description'] ?? '';
                $description = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $description);
                $description = strip_tags($description, '<p><br><ul><ol><li><strong><em><b><i><h2><h3><a>');

                $applyUrl = trim($row['apply_url'] ?? '');
                $sourceUrl = trim($row['source_url'] ?? '');

                Job::create([
                    'hash' => $hash,
                    'title' => $title,
                    'slug' => Str::slug($title) . '-' . Str::random(8),
                    'company_name' => $companyName,
                    'company_id' => null,
                    'creator_id' => null,
                    'department' => trim($row['department'] ?? '') ?: null,
                    'location' => trim($row['location'] ?? '') ?: null,
                    'country' => trim($row['country'] ?? '') ?: null,
                    'timezone' => trim($row['timezone'] ?? '') ?: null,
                    'location_type' => $this->mapLocationType($row['location_type'] ?? ''),
                    'contract_type' => $this->mapContractType($row['contract_type'] ?? ''),
                    'experience_level' => $this->mapExperience($row['experience_level'] ?? ''),
                    'vue_version' => trim($row['vue_version'] ?? '') ?: null,
                    'nuxt_version' => trim($row['nuxt_version'] ?? '') ?: null,
                    'requires_typescript' => $this->toBool($row['requires_typescript'] ?? ''),
                    'salary_min' => $this->numericOrNull($row['salary_min'] ?? null),
                    'salary_max' => $this->numericOrNull($row['salary_max'] ?? null),
                    'currency' => strtoupper(trim($row['currency'] ?? 'USD')) ?: 'USD',
                    'salary_interval' => $this->mapInterval($row['salary_interval'] ?? ''),
                    'description' => trim($description) ?: $title,
                    'apply_url' => filter_var($applyUrl, FILTER_VALIDATE_URL) ? $applyUrl : null,
                    'skills' => $this->parseCsv($row['skills'] ?? ''),
                    'benefits' => $this->parseCsv($row['benefits'] ?? ''),
                    'source' => trim($row['source'] ?? 'import') ?: 'import',
                    'source_url' => filter_var($sourceUrl, FILTER_VALIDATE_URL) ? $sourceUrl : null,
                    'is_verified' => false,
                    'status' => 'published',
                    'published_at' => now(),
                    'expires_at' => now()->addDays(30),
                ]);

                $this->imported++;
            } catch (\Throwable $e) {
                $this->skipped++;
                $this->errors[] = "Row {$rowNum}: {$e->getMessage()}";
            }
        }
    }

    private function mapLocationType(string $value): string
    {
        $value = strtolower(trim($value));
        if (str_contains($value, 'remote')) return 'remote';
        if (str_contains($value, 'hybrid')) return 'hybrid';
        if ($value === 'on_site' || $value === 'onsite') return 'on_site';
        return 'remote';
    }

    private function mapContractType(string $value): string
    {
        $value = strtolower(trim($value));
        $map = ['full' => 'full_time', 'part' => 'part_time', 'contract' => 'contract', 'freelance' => 'freelance', 'intern' => 'internship'];
        foreach ($map as $k => $v) {
            if (str_contains($value, $k)) return $v;
        }
        return 'full_time';
    }

    private function mapExperience(string $value): ?string
    {
        $value = strtolower(trim($value));
        $map = ['junior' => 'junior', 'entry' => 'junior', 'mid' => 'mid', 'senior' => 'senior', 'lead' => 'lead'];
        foreach ($map as $k => $v) {
            if (str_contains($value, $k)) return $v;
        }
        return null;
    }

    private function mapInterval(string $value): string
    {
        $value = strtolower(trim($value));
        $valid = ['yearly', 'monthly', 'weekly', 'daily', 'hourly'];
        return in_array($value, $valid) ? $value : 'yearly';
    }

    private function toBool($value): bool
    {
        if (is_bool($value)) return $value;
        $value = strtolower(trim((string) $value));
        return in_array($value, ['yes', 'true', '1', 'y']);
    }

    private function numericOrNull($value): ?int
    {
        if ($value === null || $value === '') return null;
        $num = (int) preg_replace('/[^0-9]/', '', (string) $value);
        return $num > 0 ? $num : null;
    }

    private function parseCsv($value): array
    {
        if (is_array($value)) return $value;
        if (! is_string($value) || ! strlen($value)) return [];
        return array_values(array_filter(array_map('trim', explode(',', $value))));
    }
}
