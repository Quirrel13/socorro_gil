<?php

namespace App\Enums;

enum StatusSolicitacao: string
{
    case PENDENTE = 'pendente';
    case APROVADA = 'aprovada';
    case RECUSADA = 'recusada';
}