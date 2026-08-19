<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Price extends Model
{
    public $timestamps = false;

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'id_product');
    }
}
