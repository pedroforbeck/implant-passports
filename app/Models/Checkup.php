<?php

namespace App\Models;

use Database\Factories\CheckupFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Checkup extends Model
{
    /** @use HasFactory<CheckupFactory> */
    use HasFactory;

    public const LOW_BATTERY = 20;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'device_id',
        'doctor_id',
        'checked_at',
        'battery_level',
        'notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'checked_at' => 'date',
            'battery_level' => 'integer',
        ];
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
