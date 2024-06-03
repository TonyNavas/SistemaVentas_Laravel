<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relacion polimorfica image
    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    // Relacion uno a uno
    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Atributos
    protected function stockLabel(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->attributes['stock'] >= $this->attributes['stock_minimo'] ?
                '<span class="badge badge-pill badge-primary">' . $this->attributes['stock'] . '</span>' :
                '<span class="badge badge-pill badge-danger">' . $this->attributes['stock'] . '</span>';
            }
        );
    }

    protected function precio(): Attribute
    {
        return Attribute::make(
            get: function () {
                return '<b>C$'.number_format($this->attributes['precio_venta'],2,',','.').'</b>';
            }
        );
    }

    protected function activeLabel(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->attributes['active'] ?
                '<span class="badge badge-pill badge-success">Activo</span>' :
                '<span class="badge badge-pill badge-warning">Inactivo</span>';
            }
        );
    }

    protected function imagen(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->image ? Storage::url('public/'.$this->image->url) : asset('noimage.jpg');
            }
        );
    }
}
