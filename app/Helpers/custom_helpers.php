<?php

use Illuminate\Support\Facades\Route;

if (! function_exists('safe_route')) {
    /**
     * Gera uma URL para uma rota nomeada, ou retorna a rota de fallback (#)
     * se a rota não existir.
     *
     * @param  string  $name
     * @param  array   $parameters
     * @param  bool    $absolute
     * @return string
     */
    function safe_route($name, $parameters = [], $absolute = true)
    {
        // Verifica se a rota existe no web.php
        if (Route::has($name)) {
            return route($name, $parameters, $absolute);
        }

        // Se não existir, retorna sua rota de "Em Construção" (que você nomeou como '#')
        // Se até a rota '#' sumir, retorna apenas uma string '#' para não quebrar.
        return Route::has('#') ? route('#') : '#';
    }
}
