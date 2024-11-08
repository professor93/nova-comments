<?php

declare(strict_types=1);

namespace Uzbek\NovaComments;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Uzbek\NovaComments\Models\Comment;

trait Commentable
{
    /**
     * @return MorphMany<Comment>
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
