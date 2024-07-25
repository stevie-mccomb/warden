<?php

namespace Stevie\Warden\Relations;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class WardenBelongsToMany extends BelongsToMany
{
    /**
     * Sync the intermediate tables with a list of IDs or collection of models.
     *
     * This method is specifically intended to sync warden capabilities and
     * additionally checks the given IDs and ensures that all dependency
     * capabilities are also synced.
     *
     * NOTE: This method will technically accept IDs from tables other than
     * Warden's `capabilities` table. Please ensure you only pass capability IDs
     * or there will be unintended side-effects.
     */
    public function syncWithDependencies(Collection|Model|array $ids, bool $detaching = false): array
    {
        $dependencyIds = DB::table(config('warden.tables.capability_capability'))
            ->whereIn('dependent_id', $ids)
            ->whereNotIn('dependency_id', $ids)
            ->pluck('dependency_id');

        $capabilityIds = array_unique([ ...$ids, ...$dependencyIds ]);

        return $this->sync($capabilityIds, $detaching);
    }
}
