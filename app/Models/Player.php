<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'date_of_birth'];

    public function teams()
    {
        return $this->belongsToMany(Team::class)
            ->withPivot('sort_order')
            ->orderBy('pivot_sort_order');
    }
}
