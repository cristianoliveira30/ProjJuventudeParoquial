<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class LiturgiaService
{
    private const API_URL = 'https://liturgia.up.railway.app/';

    public function getLiturgiaDoDia(): array
    {
        if (config('services.liturgia.client_fetch')) {
            return $this->dadosIndisponiveis();
        }

        $cacheKey = 'liturgia_diaria_v2_'.now()->format('Ymd');

        try {
            $cached = Cache::get($cacheKey);
            if (is_array($cached)) {
                return $cached;
            }

            $response = Http::acceptJson()
                ->timeout(5)
                ->get(self::API_URL.now()->format('d-m'));

            $payload = $response->json();
            if (
                ! $response->successful()
                || ! is_array($payload)
                || blank($payload['liturgia'] ?? null)
                || ! $this->possuiLeiturasEssenciais($payload)
            ) {
                throw new \RuntimeException('A API de liturgia retornou uma resposta inválida.');
            }

            $liturgia = $this->normalizar($payload);
            Cache::put($cacheKey, $liturgia, now()->endOfDay());

            return $liturgia;
        } catch (Throwable $e) {
            Log::warning('Falha ao conectar na API de Liturgia: '.$e->getMessage());

            return $this->dadosIndisponiveis();
        }
    }

    private function normalizar(array $liturgia): array
    {
        $normalizada = $this->dadosIndisponiveis();

        foreach (['data', 'dia', 'liturgia', 'cor', 'oferendas', 'comunhao'] as $campo) {
            if (isset($liturgia[$campo]) && is_string($liturgia[$campo]) && trim($liturgia[$campo]) !== '') {
                $normalizada[$campo] = $liturgia[$campo];
            }
        }

        foreach (['primeiraLeitura', 'salmo', 'segundaLeitura', 'evangelho', 'antifonas'] as $campo) {
            if (isset($liturgia[$campo]) && (is_string($liturgia[$campo]) || is_array($liturgia[$campo]))) {
                $normalizada[$campo] = $liturgia[$campo];
            }
        }

        return $normalizada;
    }

    private function possuiLeiturasEssenciais(array $liturgia): bool
    {
        foreach (['primeiraLeitura', 'salmo', 'evangelho'] as $campo) {
            if (! isset($liturgia[$campo]) || (! is_string($liturgia[$campo]) && ! is_array($liturgia[$campo]))) {
                return false;
            }
        }

        return true;
    }

    private function dadosIndisponiveis(): array
    {
        $aviso = 'Não foi possível carregar esta leitura automaticamente. Tente recarregar a página em alguns instantes.';

        return [
            'data' => now()->translatedFormat('d \\d\\e F \\d\\e Y'),
            'dia' => 'A liturgia diária está temporariamente indisponível.',
            'liturgia' => 'Liturgia diária',
            'cor' => 'Verde',
            'oferendas' => 'Informação indisponível no momento.',
            'comunhao' => 'Informação indisponível no momento.',
            'primeiraLeitura' => $aviso,
            'salmo' => $aviso,
            'segundaLeitura' => $aviso,
            'evangelho' => $aviso,
            'antifonas' => $aviso,
        ];
    }
}
