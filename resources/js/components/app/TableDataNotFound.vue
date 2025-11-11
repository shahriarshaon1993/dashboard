<script lang="ts" setup>
import { TableCell, TableRow } from '@/components/ui/table';
import { FrownIcon } from 'lucide-vue-next';

defineProps<{
    length: number;
}>();
</script>

<template>
    <TableRow>
        <TableCell :colspan="length + 2" class="py-3 text-center">
            <div class="flex flex-col items-center justify-center gap-2">
                <FrownIcon class="mb-1 h-14 w-14" />
                <p class="font-semibold">No Data</p>
            </div>
        </TableCell>
    </TableRow>
</template>
