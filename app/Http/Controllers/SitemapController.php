<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Madrasah;
use App\Models\User;
use App\Models\Presensi;
use App\Models\TeachingSchedule;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Static pages
        $staticPages = [
            ['url' => '/', 'priority' => '1.0', 'changefreq' => 'weekly'],
            ['url' => '/dashboard', 'priority' => '0.8', 'changefreq' => 'daily'],
            ['url' => '/panduan', 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['url' => '/presensi', 'priority' => '0.8', 'changefreq' => 'daily'],
            ['url' => '/presensi-admin', 'priority' => '0.8', 'changefreq' => 'daily'],
            ['url' => '/teaching-attendances', 'priority' => '0.8', 'changefreq' => 'daily'],
            ['url' => '/izin', 'priority' => '0.6', 'changefreq' => 'weekly'],
        ];

        foreach ($staticPages as $page) {
            $sitemap .= $this->generateUrlEntry(url($page['url']), $page['priority'], $page['changefreq']);
        }

        // Dynamic pages - Madrasah
        $madrasahs = Madrasah::select('id', 'updated_at')->get();
        foreach ($madrasahs as $madrasah) {
            $sitemap .= $this->generateUrlEntry(
                url('/masterdata/madrasah/' . $madrasah->id . '/detail'),
                '0.7',
                'weekly',
                $madrasah->updated_at->format('Y-m-d')
            );
        }

        // Dynamic pages - Teaching Schedules
        $teachingSchedules = TeachingSchedule::select('id', 'updated_at')->get();
        foreach ($teachingSchedules as $schedule) {
            $sitemap .= $this->generateUrlEntry(
                url('/teaching-schedules/' . $schedule->id),
                '0.7',
                'weekly',
                $schedule->updated_at->format('Y-m-d')
            );
        }

        $sitemap .= '</urlset>';

        return response($sitemap, 200)
            ->header('Content-Type', 'application/xml');
    }

    private function generateUrlEntry($url, $priority, $changefreq, $lastmod = null)
    {
        $entry = "  <url>\n";
        $entry .= "    <loc>{$url}</loc>\n";
        if ($lastmod) {
            $entry .= "    <lastmod>{$lastmod}</lastmod>\n";
        }
        $entry .= "    <changefreq>{$changefreq}</changefreq>\n";
        $entry .= "    <priority>{$priority}</priority>\n";
        $entry .= "  </url>\n";

        return $entry;
    }
}
