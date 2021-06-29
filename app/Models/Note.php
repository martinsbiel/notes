<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['user_id', 'title', 'content'];

    public function rules(){
        return [
            'user_id' => 'required',
            'title' => 'required',
            'content' => 'required'
        ];
    }

    public function feedback(){
        return [
            'user_id.required' => 'O campo user_id deve ser preenchido',
            'title.required' => 'O campo título deve ser preenchido',
            'content.required' => 'O campo conteúdo deve ser preenchido'
        ];
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
