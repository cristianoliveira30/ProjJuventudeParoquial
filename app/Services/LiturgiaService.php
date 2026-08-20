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
        try {
            return Cache::remember('liturgia_diaria_'.now()->format('Ymd'), now()->endOfDay(), function (): array {
                $dataFormatoApi = now()->format('d-m');
                $response = Http::acceptJson()
                    ->timeout(5)
                    ->get(self::API_URL.$dataFormatoApi);

                if ($response->successful() && is_array($response->json())) {
                    return $this->normalizar($response->json());
                }

                return $this->dadosIndisponiveis();
            });
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
