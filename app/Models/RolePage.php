<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RolePage extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'role_page';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role_id',
        'page_key',
    ];

    /**
     * Get the role that owns this page assignment.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}

