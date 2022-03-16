<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contest extends Model
{
    use HasFactory;

    protected $table = 'contest';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'contest_start', 'contest_end', 'submission_start', 'submission_end', 'options',
        'created_at', 'updated_at'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $casts = [
        'options' => 'array',
    ];

    public function diaries()
    {
        return $this->hasMany(Diary::class);
    }

    public function getIsActiveSubmissionAttribute()
    {
        $now = Carbon::now();
        if ($now >= $this->submission_start && $now <= $this->submission_end) {
            return true;
        }
        return false;
    }

    public function scopePreviousOne($query)
    {
        return $query->whereRaw('contest_start < NOW() ORDER BY contest_start DESC LIMIT 1')->get();
    }

    public function scopeCurrentAndNextTwo($query)
    {
        return $query->whereRaw('contest_start >= NOW() ORDER BY contest_start ASC LIMIT 3')->get();
    }

    public function scopeLastContest($query)
    {
        return $query->whereRaw('contest_end <= NOW() ORDER BY contest_end DESC')->first();
    }

    public function scopeAllOrdered($query)
    {
        return $query->orderBy('contest_start', 'desc')->get();
    }

    public function scopeRunningOrdered($query)
    {
        return $query->whereRaw('NOW() BETWEEN contest_start AND contest_end ORDER BY contest_start DESC')->get();
    }

    public function scopeSubmissionActiveOrdered($query)
    {
        return $query->whereRaw('NOW() BETWEEN submission_start AND submission_end ORDER BY submission_start DESC')->get();
    }
}
