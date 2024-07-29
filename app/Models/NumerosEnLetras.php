<?php

namespace App\Models;

class NumerosEnLetras
{
    private static $UNIDADES = [
        '', 'un ', 'dos ', 'tres ', 'cuatro ', 'cinco ', 'seis ', 'siete ', 'ocho ', 'nueve ', 'diez ',
        'once ', 'doce ', 'trece ', 'catorce ', 'quince ', 'dieciséis ', 'diecisiete ', 'dieciocho ', 'diecinueve ', 'veinte '
    ];

    private static $DECENAS = [
        'veinti', 'treinta ', 'cuarenta ', 'cincuenta ', 'sesenta ', 'setenta ', 'ochenta ', 'noventa ', 'cien '
    ];

    private static $CENTENAS = [
        'ciento ', 'doscientos ', 'trescientos ', 'cuatrocientos ', 'quinientos ', 'seiscientos ', 'setecientos ', 'ochocientos ', 'novecientos '
    ];

    public static function convertir($number, $currency = 'cordobas', $format = false, $decimals = 'centavos')
    {
        $number = (float)$number;

        if ($number == 0) {
            return 'Cero ' . $currency;
        }

        // Formatear el número con comas como separadores de miles
        $div_decimales = explode('.', number_format($number, 2, '.', ''));
        $base_number = $div_decimales[0];
        $decimales = isset($div_decimales[1]) ? $div_decimales[1] : '00';

        $converted = self::convertirParteEntera($base_number);

        if ($decimales == '00') {
            $decimales = 'cero';
        } else {
            $decimales = self::convertirParteEntera($decimales);
        }

        if ($format) {
            $valor_convertido = number_format($number, 2, ',', '.') . ' (' . ucfirst($converted) . ' con ' . $decimales . ' ' . $decimals . ')';
        } else {
            $valor_convertido = ucfirst($converted) . $currency . ' con ' . $decimales . ' ' . $decimals;
        }

        return $valor_convertido;
    }

    private static function convertirParteEntera($number)
    {
        $numberStr = str_pad($number, 9, '0', STR_PAD_LEFT);
        $millones = substr($numberStr, 0, 3);
        $miles = substr($numberStr, 3, 3);
        $cientos = substr($numberStr, 6);

        $output = '';

        if (intval($millones) > 0) {
            if ($millones == '001') {
                $output .= 'un millón ';
            } else {
                $output .= self::convertGroup($millones) . 'millones ';
            }
        }

        if (intval($miles) > 0) {
            if ($miles == '001') {
                $output .= 'mil ';
            } else {
                $output .= self::convertGroup($miles) . 'mil ';
            }
        }

        if (intval($cientos) > 0) {
            if ($cientos == '001') {
                $output .= 'uno ';
            } else {
                $output .= self::convertGroup($cientos);
            }
        }

        return $output;
    }

    private static function convertGroup($n)
    {
        $output = '';

        if ($n == '100') {
            return "cien ";
        }

        if ($n[0] !== '0') {
            $output .= self::$CENTENAS[$n[0] - 1];
        }

        $k = intval(substr($n, 1));

        if ($k <= 20) {
            $output .= self::$UNIDADES[$k];
        } else {
            if (($k > 30) && ($n[2] !== '0')) {
                $output .= self::$DECENAS[intval($n[1]) - 2] . 'y ' . self::$UNIDADES[intval($n[2])];
            } else {
                $output .= self::$DECENAS[intval($n[1]) - 2] . self::$UNIDADES[intval($n[2])];
            }
        }

        return $output;
    }
}

