<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

Class TaskUser extends Model{

    protected $table ="task_user";

   protected $fillable = [
        'task_id', 'user_id',
    ];

    protected $casts = [
        'task_id' => 'integer',
        'user_id' => 'integer',
    ];

    public function assignedTask()
    {
        return $this->hasMany('App\Models\Task', 'id', 'task_id');
    }
    
    public function assignedUser()
    {
        return $this->hasMany('App\Models\User', 'id', 'user_id');
    }
}