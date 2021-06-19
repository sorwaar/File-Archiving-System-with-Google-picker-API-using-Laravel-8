<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Category extends Model
{
    use HasFactory;
    protected $fillable = ['parent_id', 'name'];

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function user()
	{
		return $this->belongsTo(User::class, 'created_by', 'id');
	}

    public function files(){
        return $this->hasMany(File::class);
    }



}
