<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\DiscountTarget;
use Illuminate\Auth\Access\HandlesAuthorization;

class DiscountTargetPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:DiscountTarget');
    }

    public function view(AuthUser $authUser, DiscountTarget $discountTarget): bool
    {
        return $authUser->can('View:DiscountTarget');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:DiscountTarget');
    }

    public function update(AuthUser $authUser, DiscountTarget $discountTarget): bool
    {
        return $authUser->can('Update:DiscountTarget');
    }

    public function delete(AuthUser $authUser, DiscountTarget $discountTarget): bool
    {
        return $authUser->can('Delete:DiscountTarget');
    }

    public function restore(AuthUser $authUser, DiscountTarget $discountTarget): bool
    {
        return $authUser->can('Restore:DiscountTarget');
    }

    public function forceDelete(AuthUser $authUser, DiscountTarget $discountTarget): bool
    {
        return $authUser->can('ForceDelete:DiscountTarget');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:DiscountTarget');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:DiscountTarget');
    }

    public function replicate(AuthUser $authUser, DiscountTarget $discountTarget): bool
    {
        return $authUser->can('Replicate:DiscountTarget');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:DiscountTarget');
    }

}