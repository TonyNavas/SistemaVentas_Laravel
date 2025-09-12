<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
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

    public function image(){
        return $this->morphOne(Image::class, 'imageable');
    }

    // Relaciones

    public function sales(){
        return $this->hasMany(Sale::class);
    }
}
