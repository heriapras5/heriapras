<?php

namespace App\Http\Middleware;

use App\Models\PageView;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TrackVisitor
{
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->environment('testing') || $request->is('admin*') || $request->isMethod('POST')) {
            return $next($request);
        }

        $agent = $request->userAgent();
        $deviceType = 'desktop';
        if (preg_match('/(android|iphone|ipad|mobile)/i', $agent ?? '')) {
            $deviceType = preg_match('/(ipad|tablet)/i', $agent ?? '') ? 'tablet' : 'mobile';
        }

        // Statistik pengunjung tidak boleh merobohkan situs:
        // kegagalan catat (mis. privilege DB kurang) hanya ditulis ke log.
        try {
            PageView::create([
                'ip_address' => $request->ip(),
                'user_agent' => $agent,
                'page_url' => mb_substr($request->fullUrl(), 0, 255),
                'page_path' => mb_substr($request->path(), 0, 255),
                'device_type' => $deviceType,
            ]);
        } catch (Throwable $e) {
            Log::warning('TrackVisitor gagal mencatat page view: '.$e->getMessage());
        }

        return $next($request);
    }
}
