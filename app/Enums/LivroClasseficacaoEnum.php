<?php
    namespace App\Enums;

    enum LivroClasseficacaoEnum : string {
        
        case LIVRE = 'livre';

        case JUVENIL = 'juvenil';

        case MADURO = 'maduro';
        
        case ADULTO = 'adulto';
    }