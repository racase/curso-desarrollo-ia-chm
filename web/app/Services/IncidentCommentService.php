<?php

namespace App\Services;

use App\Models\Incident;
use App\Models\IncidentComment;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class IncidentCommentService
{
    public function publish(User $author, Incident $incident, string $content): IncidentComment
    {
        Gate::forUser($author)->authorize('comment', $incident);

        $content = trim($content);

        if ($content === '') {
            throw ValidationException::withMessages([
                'content' => 'El comentario es obligatorio.',
            ]);
        }

        return $incident->comments()->create([
            'author_id' => $author->getKey(),
            'content' => $content,
        ]);
    }
}
