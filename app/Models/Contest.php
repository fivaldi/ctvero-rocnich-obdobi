<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
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

    public function scopeLastYear($query)
    {
        return $query->whereRaw('contest_start > DATE_SUB(NOW(), INTERVAL 1 YEAR) ORDER BY contest_start DESC LIMIT 4')->get();
    }

    public function scopeLastContest($query)
    {
        return $query->whereRaw('contest_end <= NOW() ORDER BY contest_end DESC')->first();
    }

    public function scopeAllOrdered($query)
    {
        return $query->orderBy('contest_start', 'desc')->get();
    }

    public function scopeSubmissionActiveOrdered($query)
    {
        return $query->whereRaw('NOW() BETWEEN submission_start AND submission_end ORDER BY submission_start DESC')->get();
    }
}
