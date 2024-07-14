<?php

namespace App\Policies\Stranding;

use App\Models\User;
use App\Models\Stranding\Family;
use Illuminate\Auth\Access\HandlesAuthorization;

class FamilyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_stranding::family');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Family $family): bool
    {
        return $user->can('view_stranding::family');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_stranding::family');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Family $family): bool
    {
        return $user->can('update_stranding::family');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Family $family): bool
    {
        return $user->can('delete_stranding::family');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_stranding::family');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Family $family): bool
    {
        return $user->can('force_delete_stranding::family');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_stranding::family');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Family $family): bool
    {
        return $user->can('restore_stranding::family');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_stranding::family');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Family $family): bool
    {
        return $user->can('replicate_stranding::family');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_stranding::family');
    }
}
