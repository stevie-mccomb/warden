<?php

namespace Stevie\Warden\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stevie\Warden\Database\Factories\RoleFactory;
use Stevie\Warden\Events\RoleCreated;
use Stevie\Warden\Events\RoleCreating;
use Stevie\Warden\Events\RoleDeleted;
use Stevie\Warden\Events\RoleDeleting;
use Stevie\Warden\Events\RolePurged;
use Stevie\Warden\Events\RolePurging;
use Stevie\Warden\Events\RoleReplicating;
use Stevie\Warden\Events\RoleRestored;
use Stevie\Warden\Events\RoleRestoring;
use Stevie\Warden\Events\RoleRetrieved;
use Stevie\Warden\Events\RoleSaved;
use Stevie\Warden\Events\RoleSaving;
use Stevie\Warden\Events\RoleTrashed;
use Stevie\Warden\Events\RoleUpdated;
use Stevie\Warden\Events\RoleUpdating;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The event map for the model.
     *
     * @var array<string, string>
     */
    protected $dispatchesEvents = [
        'created' => RoleCreated::class,
        'creating' => RoleCreating::class,
        'deleted' => RoleDeleted::class,
        'deleting' => RoleDeleting::class,
        'forceDeleting' => RolePurged::class,
        'forceDeleted' => RolePurging::class,
        'replicating' => RoleReplicating::class,
        'restored' => RoleRestored::class,
        'restoring' => RoleRestoring::class,
        'retrieved' => RoleRetrieved::class,
        'saved' => RoleSaved::class,
        'saving' => RoleSaving::class,
        'trashed' => RoleTrashed::class,
        'updated' => RoleUpdated::class,
        'updating' => RoleUpdating::class,
    ];

    /**
     * The attributes that are mass-assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Return the capabilities that this role grants.
     */
    public function capabilities(): BelongsToMany
    {
        return $this->belongsToMany(Capability::class);
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return RoleFactory::new();
    }
}
