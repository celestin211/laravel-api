<?php

namespace App\Policies;

use App\Models\Offer;
use App\Models\User;

class OfferPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view offers list
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Offer $offer): bool
    {
        // All authenticated users can view offers
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Editors and admins can create offers
        return $user->role->isEditor();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Offer $offer): bool
    {
        // Admins can update any offer
        if ($user->role->isAdmin()) {
            return true;
        }

        // Editors can update offers (could be restricted to their own offers in the future)
        return $user->role->isEditor();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Offer $offer): bool
    {
        // Only admins can delete offers
        return $user->role->isAdmin();
    }

    /**
     * Determine whether the user can publish the model.
     */
    public function publish(User $user, Offer $offer): bool
    {
        // Editors and admins can publish offers
        return $user->role->isEditor();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Offer $offer): bool
    {
        // Only admins can restore offers
        return $user->role->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Offer $offer): bool
    {
        // Only admins can permanently delete offers
        return $user->role->isAdmin();
    }
}
