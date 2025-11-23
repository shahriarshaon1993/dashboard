<script setup lang="ts">
import { TableHead } from '@/components/ui/table';
import { ArrowDownUp, ArrowUpDown } from 'lucide-vue-next';

type SortOrder = 'asc' | 'desc';

const props = defineProps<{
    label: string;
    sortKey: string;
    activeSortKey?: string;
    sortOrder?: SortOrder;
}>();

const emit = defineEmits<{
    (e: 'sort', key: string): void;
}>();

const handleClick = () => {
    emit('sort', props.sortKey);
};
</script>

<template>
    <TableHead class="" @click="handleClick">
        <div
            class="flex cursor-pointer items-center justify-between gap-2 select-none"
        >
            <div>{{ label }}</div>
            <div>
                <ArrowUpDown
                    v-if="sortOrder === 'asc'"
                    class="size-4 text-muted-foreground"
                />
                <ArrowDownUp v-else class="size-4 text-muted-foreground" />
            </div>
        </div>
    </TableHead>
</template>
