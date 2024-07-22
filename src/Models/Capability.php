<?php

namespace Stevie\Warden\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stevie\Warden\Database\Factories\CapabilityFactory;
use Stevie\Warden\Events\CapabilityCreated;
use Stevie\Warden\Events\CapabilityCreating;
use Stevie\Warden\Events\CapabilityDeleted;
use Stevie\Warden\Events\CapabilityDeleting;
use Stevie\Warden\Events\CapabilityPurged;
use Stevie\Warden\Events\CapabilityPurging;
use Stevie\Warden\Events\CapabilityReplicating;
use Stevie\Warden\Events\CapabilityRestored;
use Stevie\Warden\Events\CapabilityRestoring;
use Stevie\Warden\Events\CapabilityRetrieved;
use Stevie\Warden\Events\CapabilitySaved;
use Stevie\Warden\Events\CapabilitySaving;
use Stevie\Warden\Events\CapabilityTrashed;
use Stevie\Warden\Events\CapabilityUpdated;
use Stevie\Warden\Events\CapabilityUpdating;

class Capability extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The event map for the model.
     *
     * @var array<string, string>
     */
    protected $dispatchesEvents = [
        'retrieved' => CapabilityRetrieved::class,
        'creating' => CapabilityCreating::class,
        'created' => CapabilityCreated::class,
        'updating' => CapabilityUpdating::class,
        'updated' => CapabilityUpdated::class,
        'saving' => CapabilitySaving::class,
        'saved' => CapabilitySaved::class,
        'deleting' => CapabilityDeleting::class,
        'deleted' => CapabilityDeleted::class,
        'trashed' => CapabilityTrashed::class,
        'forceDeleting' => CapabilityPurging::class,
        'forceDeleted' => CapabilityPurged::class,
        'restoring' => CapabilityRestoring::class,
        'restored' => CapabilityRestored::class,
        'replicating' => CapabilityReplicating::class,
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
     * Return the other capabilities that this capability depends on.
     */
    public function dependencies(): BelongsToMany
    {
        return $this->belongsToMany(Capability::class, null, 'dependent_id', 'dependency_id');
    }

    /**
     * Return the other capabilities that depend on this capability.
     */
    public function dependents(): BelongsToMany
    {
        return $this->belongsToMany(Capability::class, null, 'dependency_id', 'dependent_id');
    }

    /**
     * Return the roles that grant this capability.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return CapabilityFactory::new();
    }
}
