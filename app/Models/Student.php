<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'school_class_id',
        'parent_phone',
        'address',
        'social_status',
        'medical_notes',
        'status',
        'archived_at',
        'photo_path',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'archived_at' => 'datetime',
    ];

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->last_name.' '.$this->first_name);
    }

    public function averageScore(): ?float
    {
        if (!$this->relationLoaded('grades')) {
            $this->load('grades');
        }

        if ($this->grades->isEmpty()) {
            return null;
        }

        $weightedTotal = $this->grades->sum(fn (Grade $grade) => $grade->score * $grade->coefficient);
        $coeffTotal = $this->grades->sum('coefficient');

        if ($coeffTotal === 0.0) {
            return null;
        }

        return round($weightedTotal / $coeffTotal, 2);
    }

    public function averageLabel(): string
    {
        $average = $this->averageScore();

        return $average === null ? 'N/A' : number_format($average, 2);
    }

    public function mention(): string
    {
        $average = $this->averageScore();

        if ($average === null) {
            return 'Non évalué';
        }

        return match (true) {
            $average >= 16 => 'Très bien',
            $average >= 14 => 'Bien',
            $average >= 12 => 'Assez bien',
            $average >= 10 => 'Passable',
            default => 'Insuffisant',
        };
    }
}
