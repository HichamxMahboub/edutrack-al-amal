<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'level',
        'school_year',
        'description',
        'teacher_id',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function averageScore(): ?float
    {
        $grades = Grade::query()
            ->whereIn('student_id', $this->students()->select('id'))
            ->get();

        if ($grades->isEmpty()) {
            return null;
        }

        $weightedTotal = $grades->sum(fn (Grade $grade) => $grade->score * $grade->coefficient);
        $coeffTotal = $grades->sum('coefficient');

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
}
