<?php

namespace Stevie\Warden\Gates;

use Illuminate\Contracts\Auth\Authenticatable;
use Stevie\Warden\Traits\HasRoles;

class Before
{
    /**
     * If the given user has the given capability and no resource is provided (for a policy),
     * then simply check if the user has the given capability assigned to them via their active role.
     */
    public function before(Authenticatable $user, string $capability, mixed $resource = null): ?bool
    {
        /** @var \Stevie\Warden\Traits\HasRoles $user */
        if (empty($resource) && in_array(HasRoles::class, class_uses_recursive($user)) && $user->capabilities()->where('capabilities.slug', $capability)->exists()) return true;
        return null;
    }
}
