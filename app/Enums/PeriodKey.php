<?php

namespace App\Enums;

enum PeriodKey: string
{
    case HOJE = 'hoje';
    case ONTEM = 'ontem';
    case SEMANA_ATUAL = 'semana-atual';
    case SEMANA_PASSADA = 'semana-passada';
    case MES_ATUAL = 'mes-atual';
    case MES_PASSADO = 'mes-passado';
    case PERIODO = 'periodo';
}