<?php

namespace App\Core;

class Response
{
    public function setStatusCode(int $code): self
    {
        http_response_code($code);
        return $this;
    }

    public function json(mixed $data, int $statusCode = 200): void
    {
        $this->setStatusCode($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    public function redirect(string $url): void
    {
        // Support relative path with base url
        if (!preg_match('#^https?://#i', $url)) {
            $url = base_url($url);
        }
        header("Location: {$url}");
        exit;
    }
}
