<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ForumThread extends Model
{
    /**
     * Daftar kategori forum yang diizinkan.
     *
     * @var list<string>
     */
    public const CATEGORIES = ['umum', 'cerita', 'tips', 'tanya'];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'author_name',
        'title',
        'body',
        'category',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [];
    }

    /**
     * Akun pengguna yang membuat thread ini (boleh null untuk anonimitas tampilan).
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Balasan-balasan yang tergabung dalam thread ini.
     *
     * @return HasMany<ForumReply, $this>
     */
    public function replies(): HasMany
    {
        return $this->hasMany(ForumReply::class, 'thread_id');
    }
}
