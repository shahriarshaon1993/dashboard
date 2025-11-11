<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

final readonly class UserExport implements FromCollection, WithHeadings
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
     * @return Collection<int, array{sl: int, name: string, email: string, phone: string, created_at: CarbonInterface, updated_at: CarbonInterface, is_active: string}>
     */
    public function collection(): Collection
    {
        $query = User::query();

        if ($this->ids !== []) {
            $query->whereIn('id', $this->ids);
        }

        return $query->get()->map(fn (User $user, int $i): array => [
            'sl' => $i + 1,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
            'is_active' => $user->is_active->label(),
        ]);
    }

    /**
     * @return string[]
     */
    public function headings(): array
    {
        return ['SL', 'name', 'email', 'phone', 'created_at', 'updated_at', 'is_active'];
    }
}
