<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model{

    use HasFactory;

    const status = ['completed','in-progress','pending'];

    protected $fillable =  ['title','descriptions', 'due_date','status'];
    
    public function assignedUsers()
    {
        return $this->belongsToMany('App\Models\User');
    }

}