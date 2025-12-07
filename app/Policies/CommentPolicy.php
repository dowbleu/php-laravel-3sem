<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    /**
     * Determine if the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Все могут просматривать комментарии
    }

    /**
     * Determine if the user can view the model.
     */
    public function view(User $user, Comment $comment): bool
    {
        return true; // Все могут просматривать комментарии
    }

    /**
     * Determine if the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Все авторизованные пользователи могут создавать комментарии
    }

    /**
     * Determine if the user can update the model.
     */
    public function update(User $user, Comment $comment): bool
    {
        return $user->isModerator();
    }

    /**
     * Determine if the user can delete the model.
     */
    public function delete(User $user, Comment $comment): bool
    {
        return $user->isModerator();
    }
}