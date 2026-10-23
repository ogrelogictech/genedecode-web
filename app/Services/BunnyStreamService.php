<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BunnyStreamService
{
    protected string $libraryId;
    protected string $apiKey;
    protected string $tokenKey;
    protected string $cdnUrl;

    public function __construct()
    {
        $this->libraryId = (string) config('services.bunny.library_id');
        $this->apiKey = (string) config('services.bunny.api_key');
        $this->tokenKey = (string) config('services.bunny.token_key');
        $this->cdnUrl = rtrim(
            (string) config(
                'services.bunny.cdn_url',
                'https://iframe.mediadelivery.net'
            ),
            '/'
        );
    }

    /**
     * Generate a token-authenticated secure embed URL for Bunny Stream.
     */
    public function generateEmbedUrl(
        string $videoId,
        int $expiresInSeconds = 7200
    ): string {
        $expires = time() + $expiresInSeconds;

        $hashableBase = $this->tokenKey . $videoId . $expires;

        $token = hash('sha256', $hashableBase);

        return sprintf(
            '%s/embed/%s/%s?token=%s&expires=%d&autoplay=true&enablejsapi=true',
            $this->cdnUrl,
            $this->libraryId,
            $videoId,
            $token,
            $expires
        );
    }

    public function getLibraryVideos(int $page = 1, int $itemsPerPage = 100): array
    {
        $response = Http::withHeaders([
            'AccessKey' => $this->apiKey,
            'accept' => 'application/json',
        ])->get("https://video.bunnycdn.com/library/{$this->libraryId}/videos", [
            'page' => $page,
            'itemsPerPage' => $itemsPerPage,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return [];
    }

    public function getVideo(string $guid): ?array
    {
        $response = Http::withHeaders([
            'AccessKey' => $this->apiKey,
            'accept' => 'application/json',
        ])->get("https://video.bunnycdn.com/library/{$this->libraryId}/videos/{$guid}");

        return $response->successful() ? $response->json() : null;
    }
}