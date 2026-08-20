<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;

class LiturgiaService
{
    // URL base da API comunitária estável
    private const API_URL = 'https://liturgia.up.railway.app/';

    public function getLiturgiaDoDia()
    {
        try {
            return Cache::remember('liturgia_diaria', now()->endOfDay(), function () {
                // Formata o dia atual no padrão aceito pela API (Ex: "19-08")
                $dataFormatoApi = now()->format('d-m');
                
                $response = Http::timeout(5)
                    ->withoutVerifying()
                    ->get(self::API_URL . $dataFormatoApi);

                if ($response->successful()) {
                    return $response->json();
                }

                return $this->getDadosFicticios();
            });
        } catch (Exception $e) {
            Log::warning('Falha ao conectar na API de Liturgia: ' . $e->getMessage());
            return $this->getDadosFicticios();
        }
    }

    private function getDadosFicticios(): array
    {
        return [
            'liturgia' => 'Tempo Comum (Modo de Segurança)',
            'cor' => 'Verde',
            'leitura1' => 'Não foi possível carregar as leituras automaticamente.',
            'salmo' => 'Verifique sua conexão.',
            'evangelho' => 'Tente recarregar a página.'
        ];
    }
}
