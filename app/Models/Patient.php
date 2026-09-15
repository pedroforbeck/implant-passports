<?php

namespace App\Models;

use Database\Factories\PatientFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Patient extends Model
{
    /** @use HasFactory<PatientFactory> */
    use HasFactory;

    public const BLOOD_TYPES = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'doctor_id',
        'name',
        'cpf',
        'birth_date',
        'blood_type',
        'phone',
        'emergency_contact_name',
        'emergency_contact_phone',
        'notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }

    public function checkups(): HasManyThrough
    {
        return $this->hasManyThrough(Checkup::class, Device::class);
    }

    public function scopeVisibleTo(Builder $query, User $user): void
    {
        if ($user->isAdmin()) {
            return;
        }

        if ($user->isDoctor()) {
            $query->where('doctor_id', $user->id);

            return;
        }

        $query->where('user_id', $user->id);
    }

    public function scopeSearch(Builder $query, ?string $term): void
    {
        if (blank($term)) {
            return;
        }

        $query->where(function (Builder $query) use ($term) {
            $query->whereLike('name', "%{$term}%")
                ->orWhereLike('cpf', "%{$term}%");
        });
    }

    protected function age(): Attribute
    {
        return Attribute::get(fn () => $this->birth_date?->age);
    }
}
