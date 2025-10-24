<?php

namespace App\Models;

use App\Enums\TodoStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TodoModel extends Model
{
    use HasFactory;
    protected $table = 'todo_model';
    protected $primaryKey = 'id';
    protected $fillable=[
        'title',
        'body',
        'completed',
        ];

    protected $casts = [
        'completed' => TodoStatus::class
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
