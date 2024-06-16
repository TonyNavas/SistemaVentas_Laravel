<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Table extends Model
{
    use HasFactory;

    protected $fillable = ['numero_mesa', 'status'];

    // Relacion muchos a muchos
    public function products()
    {
        return $this->belongsToMany(Product::class, 'table_products')->withPivot('quantity');
    }

    protected static function booted()
    {
        static::creating(function ($table) {
            $table->code = self::generateNextTableCode();
        });
    }

    public static function generateNextTableCode()
    {
        $existingCodes = self::pluck('code')->toArray();

        // Si no hay mesas aún, el próximo código será M001
        if (empty($existingCodes)) {
            return 'MESA:001';
        }

        sort($existingCodes);

        // Encuentra el siguiente código disponible en la secuencia
        foreach ($existingCodes as $index => $code) {
            $nextCode = 'MESA:' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            if ($code !== $nextCode) {
                return $nextCode;
            }
        }

        // Si no hay huecos en la secuencia, el próximo código será el siguiente después del último
        $lastCode = end($existingCodes);
        $nextNumber = (int) filter_var($lastCode, FILTER_SANITIZE_NUMBER_INT) + 1;
        return 'MESA:' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
