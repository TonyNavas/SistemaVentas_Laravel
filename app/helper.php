<?php

    //devolver numero en formato moneda

use App\Models\NumerosEnLetras;

    function money($number)
    {
        return 'C$' .number_format($number,0,'.',',');
    }

    function numeroLetras ($number){
        return NumerosEnLetras::convertir($number,'Cordobas', false, 'Centavos');
    }
