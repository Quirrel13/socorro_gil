<?php

namespace App\Enums;

enum NomeRole: string
{
    case GERENTE_GERAL = 'gerente_geral';
    case GERENTE_CONTA = 'gerente_conta';
    case CLIENTE = 'cliente';
}