<script setup lang="ts">
import {
    Pagination,
    PaginationContent,
    PaginationEllipsis,
    PaginationItem,
    PaginationNext,
    PaginationPrevious,
} from '@/components/ui/pagination';
import { PaginationMetaLink } from '@/types';
import { computed } from 'vue';

interface Props {
    currentPage: number;
    lastPage: number;
    total: number;
    perPage: number;
    links?: PaginationMetaLink[];
    showPageInfo?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    links: () => [],
    showPageInfo: true,
});

defineEmits<{
    (e: 'page-change', page: number): void;
}>();

const hasPrev = computed(() => {
    const prevLink = props.links?.find(
        (link) => link.label === '&laquo; Previous',
    );
    return !!prevLink?.url;
});

const hasNext = computed(() => {
    const nextLink = props.links?.find((link) => link.label === 'Next &raquo;');
    return !!nextLink?.url;
});
</script>

<template>
    <div class="flex items-center justify-between px-4 py-2">
        <div
            v-if="showPageInfo"
            class="flex items-center justify-center text-sm font-medium"
        >
            Page {{ currentPage }} of {{ lastPage }}
        </div>

        <div class="flex items-center space-x-2">
            <Pagination
                v-slot="{ page }"
                :items-per-page="perPage"
                :total="total"
                :default-page="currentPage"
            >
                <PaginationContent v-slot="{ items }">
                    <PaginationPrevious
                        :disabled="!hasPrev"
                        @click="$emit('page-change', currentPage - 1)"
                    />

                    <template
                        v-for="(item, index) in items"
                        :key="`page-${index}`"
                    >
                        <PaginationItem
                            v-if="item.type === 'page'"
                            :value="item.value"
                            :is-active="item.value === page"
                            @click="$emit('page-change', item.value)"
                        >
                            {{ item.value }}
                        </PaginationItem>

                        <PaginationEllipsis v-else />
                    </template>

                    <PaginationNext
                        :disabled="!hasNext"
                        @click="$emit('page-change', currentPage + 1)"
                    />
                </PaginationContent>
            </Pagination>
        </div>
    </div>
</template>
