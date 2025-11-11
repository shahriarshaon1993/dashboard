<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Role;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

final readonly class RoleExport implements FromCollection
{
    /**
     * @param  int[]  $ids
     */
    public function __construct(
        private array $ids = []
    ) {
        //
    }

    /**
     * @return Collection<int, Role>
     */
    public function collection(): Collection
    {
        if ($this->ids !== []) {
            return Role::query()
                ->whereIn('id', $this->ids)
                ->get();
        }

        return Role::all();
    }
}
