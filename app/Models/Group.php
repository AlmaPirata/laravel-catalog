<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    public $timestamps = false;

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'id_parent');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Group::class, 'id_parent');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'id_group');
    }
}
