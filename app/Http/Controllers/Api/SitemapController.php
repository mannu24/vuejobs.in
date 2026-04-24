<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Company;
use App\Models\Job;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $siteUrl = rtrim(config('app.frontend_url', 'https://vuejobs.in'), '/');

        $urls = collect();

        // Static pages
        $urls->push(['loc' => $siteUrl . '/', 'priority' => '1.0', 'changefreq' => 'daily']);
        $urls->push(['loc' => $siteUrl . '/jobs', 'priority' => '0.9', 'changefreq' => 'daily']);
        $urls->push(['loc' => $siteUrl . '/about', 'priority' => '0.5', 'changefreq' => 'monthly']);
        $urls->push(['loc' => $siteUrl . '/blog', 'priority' => '0.8', 'changefreq' => 'daily']);

        // Published jobs
        Job::query()
            ->published()
            ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>=', now()))
            ->select(['slug', 'updated_at'])
            ->orderByDesc('updated_at')
            ->chunk(500, function ($jobs) use ($urls, $siteUrl) {
                foreach ($jobs as $job) {
                    $urls->push([
                        'loc' => $siteUrl . '/jobs/' . $job->slug,
                        'lastmod' => $job->updated_at->toW3cString(),
                        'priority' => '0.8',
                        'changefreq' => 'weekly',
                    ]);
                }
            });

        // Public companies
        Company::query()
            ->where('is_public', true)
            ->select(['slug', 'updated_at'])
            ->orderByDesc('updated_at')
            ->chunk(500, function ($companies) use ($urls, $siteUrl) {
                foreach ($companies as $company) {
                    $urls->push([
                        'loc' => $siteUrl . '/companies/' . $company->slug,
                        'lastmod' => $company->updated_at->toW3cString(),
                        'priority' => '0.6',
                        'changefreq' => 'weekly',
                    ]);
                }
            });

        // Published blogs
        Blog::query()
            ->where('status', 'published')
            ->select(['slug', 'updated_at'])
            ->orderByDesc('updated_at')
            ->chunk(500, function ($blogs) use ($urls, $siteUrl) {
                foreach ($blogs as $blog) {
                    $urls->push([
                        'loc' => $siteUrl . '/blog/' . $blog->slug,
                        'lastmod' => $blog->updated_at->toW3cString(),
                        'priority' => '0.7',
                        'changefreq' => 'weekly',
                    ]);
                }
            });

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $url) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>{$url['loc']}</loc>\n";
            if (isset($url['lastmod'])) {
                $xml .= "    <lastmod>{$url['lastmod']}</lastmod>\n";
            }
            $xml .= "    <changefreq>{$url['changefreq']}</changefreq>\n";
            $xml .= "    <priority>{$url['priority']}</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }
}
