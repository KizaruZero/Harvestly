<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\DiscountRedemption;
use Illuminate\Auth\Access\HandlesAuthorization;

class DiscountRedemptionPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:DiscountRedemption');
    }

    public function view(AuthUser $authUser, DiscountRedemption $discountRedemption): bool
    {
        return $authUser->can('View:DiscountRedemption');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:DiscountRedemption');
    }

    public function update(AuthUser $authUser, DiscountRedemption $discountRedemption): bool
    {
        return $authUser->can('Update:DiscountRedemption');
    }

    public function delete(AuthUser $authUser, DiscountRedemption $discountRedemption): bool
    {
        return $authUser->can('Delete:DiscountRedemption');
    }

    public function restore(AuthUser $authUser, DiscountRedemption $discountRedemption): bool
    {
        return $authUser->can('Restore:DiscountRedemption');
    }

    public function forceDelete(AuthUser $authUser, DiscountRedemption $discountRedemption): bool
    {
        return $authUser->can('ForceDelete:DiscountRedemption');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:DiscountRedemption');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:DiscountRedemption');
    }

    public function replicate(AuthUser $authUser, DiscountRedemption $discountRedemption): bool
    {
        return $authUser->can('Replicate:DiscountRedemption');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:DiscountRedemption');
    }

}