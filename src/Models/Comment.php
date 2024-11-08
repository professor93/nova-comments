<?php

declare(strict_types=1);

namespace Uzbek\NovaComments\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Comment extends Model
{
    /**
     * The table associated with the model.
     */
    protected string $table = 'nova_comments';

    /**
     * The "booting" method of the model.
     */
    public static function boot(): void
    {
        parent::boot();

        static::creating(function (self $comment): void {
            $comment->comment = filter_var($comment->comment, FILTER_SANITIZE_SPECIAL_CHARS);

            if (auth()->check()) {
                $comment->commenter_id = auth()->id();
            }
        });
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function commenter(): BelongsTo
    {
        $commenterModel = config("nova-comments.commenter.nova-resource", 'stdClass')::$model ?? config('auth.providers.users.model');
        return $this->belongsTo($commenterModel, 'commenter_id');
    }
}
