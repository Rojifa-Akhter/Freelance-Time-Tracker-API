<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $guarded = ['id'];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function timeLogs()
    {
        return $this->hasMany(TimeLog::class);
    }

}
