<?php

namespace App\Services;

class BunnyStreamService
{
    protected string $libraryId;
    protected string $apiKey;
    protected string $tokenKey;
    protected string $cdnUrl;

    public function __construct()
    {
        $this->libraryId = config('services.bunny.library_id') ?? '';
        $this->apiKey = config('services.bunny.api_key') ?? '';
        $this->tokenKey = config('services.bunny.token_key') ?? '';
        $this->cdnUrl = config('services.bunny.cdn_url', 'https://iframe.mediadelivery.net');
    }

    /**
     * Generate a token-authenticated secure embed URL for Bunny Stream.
     */
    public function generateEmbedUrl(string $videoId, int $expiresInSeconds = 7200): string
    {
        $expires = time() + $expiresInSeconds;
        
        // Signed token generation for security
        $hashableBase = $this->tokenKey . $videoId . $expires;
        $token = hash('sha256', $hashableBase);

        return sprintf(
            '%s/embed/%s/%s?token=%s&expires=%d&autoplay=true',
            $this->cdnUrl,
            $this->libraryId,
            $videoId,
            $token,
            $expires
        );
    }
}