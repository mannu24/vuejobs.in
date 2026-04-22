<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class JobImportTemplate implements FromArray, WithHeadings, WithStyles, WithColumnWidths, WithTitle
{
    public function title(): string
    {
        return 'Jobs';
    }

    public function headings(): array
    {
        return [
            'title',
            'company_name',
            'department',
            'location',
            'country',
            'timezone',
            'location_type',
            'contract_type',
            'experience_level',
            'vue_version',
            'nuxt_version',
            'requires_typescript',
            'salary_min',
            'salary_max',
            'currency',
            'salary_interval',
            'description',
            'apply_url',
            'skills',
            'benefits',
            'source',
            'source_url',
        ];
    }

    public function array(): array
    {
        return [
            [
                'Senior Vue.js Developer',
                'TechCorp India',
                'Engineering',
                'Bangalore',
                'India',
                'Asia/Kolkata',
                'remote',
                'full_time',
                'senior',
                '3',
                '3',
                'yes',
                '1500000',
                '2500000',
                'INR',
                'yearly',
                'We are looking for a senior Vue.js developer with 5+ years of experience to lead our frontend team. You will work with Vue 3, Nuxt 3, Pinia, and TypeScript.',
                'https://techcorp.com/careers/vue-senior',
                'Vue.js, Nuxt, TypeScript, Pinia, TailwindCSS',
                'Health Insurance, Remote Work, Learning Budget',
                'linkedin',
                'https://linkedin.com/jobs/123456',
            ],
            [
                'React Frontend Engineer',
                'StartupXYZ',
                'Product',
                'Mumbai',
                'India',
                'Asia/Kolkata',
                'hybrid',
                'full_time',
                'mid',
                '',
                '',
                'no',
                '800000',
                '1400000',
                'INR',
                'yearly',
                'Join our product team as a React Frontend Engineer. Build modern web apps using React, Next.js, and TypeScript.',
                'https://startupxyz.com/jobs/react-fe',
                'React, Next.js, TypeScript, Redux, TailwindCSS',
                'Flexible Hours, Stock Options',
                'naukri',
                'https://naukri.com/jobs/789',
            ],
            [
                'Vue.js + Laravel Full Stack Developer',
                'WebAgency Co',
                'Development',
                'Remote',
                'United States',
                'America/New_York',
                'remote',
                'contract',
                'mid',
                '3',
                '',
                'yes',
                '80000',
                '120000',
                'USD',
                'yearly',
                'Looking for a full stack developer proficient in Vue.js and Laravel to build client projects. Must have experience with REST APIs and database design.',
                'https://webagency.co/careers/fullstack',
                'Vue.js, Laravel, PHP, MySQL, REST API',
                'Remote Work, Flexible Schedule',
                'indeed',
                'https://indeed.com/jobs/456',
            ],
            [
                'Junior React Developer',
                'EduTech Solutions',
                'Engineering',
                'Delhi NCR',
                'India',
                'Asia/Kolkata',
                'on_site',
                'full_time',
                'junior',
                '',
                '',
                'no',
                '400000',
                '700000',
                'INR',
                'yearly',
                'Great opportunity for freshers! Join as a Junior React Developer and learn from experienced mentors. Knowledge of HTML, CSS, JavaScript and basic React required.',
                'https://edutech.in/careers/junior-react',
                'React, JavaScript, HTML, CSS',
                'Mentorship, Training, Health Insurance',
                'naukri',
                '',
            ],
            [
                'Nuxt.js Frontend Architect',
                'FinanceApp Ltd',
                'Architecture',
                'London',
                'United Kingdom',
                'Europe/London',
                'hybrid',
                'full_time',
                'lead',
                '3',
                '3',
                'yes',
                '90000',
                '130000',
                'GBP',
                'yearly',
                'Lead the frontend architecture for our fintech platform built on Nuxt 3. You will define standards, mentor developers, and drive performance optimization.',
                'https://financeapp.co.uk/careers/nuxt-architect',
                'Nuxt, Vue.js, TypeScript, GraphQL, Performance',
                'Pension, Private Healthcare, Bonus',
                'linkedin',
                'https://linkedin.com/jobs/789012',
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 32, // title
            'B' => 22, // company_name
            'C' => 16, // department
            'D' => 16, // location
            'E' => 18, // country
            'F' => 18, // timezone
            'G' => 14, // location_type
            'H' => 14, // contract_type
            'I' => 16, // experience_level
            'J' => 13, // vue_version
            'K' => 13, // nuxt_version
            'L' => 18, // requires_typescript
            'M' => 12, // salary_min
            'N' => 12, // salary_max
            'O' => 10, // currency
            'P' => 15, // salary_interval
            'Q' => 50, // description
            'R' => 40, // apply_url
            'S' => 40, // skills
            'T' => 40, // benefits
            'U' => 12, // source
            'V' => 40, // source_url
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1E293B'],
                ],
            ],
        ];
    }
}
