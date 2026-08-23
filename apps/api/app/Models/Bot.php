<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['workspace_id', 'telegram_id', 'telegram_username', 'display_name', 'token'])]
class Bot extends Model
{
    use HasUlids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $hidden = ['token'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['token' => 'encrypted'];
    }

    /**
     * @return BelongsTo<Workspace, $this>
     */
    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function maskedToken(): string
    {
        return str_repeat('*', 8).substr($this->token, -4);
    }
}
