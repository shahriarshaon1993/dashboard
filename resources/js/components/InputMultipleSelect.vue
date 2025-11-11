<script lang="ts" setup>
import { Badge } from '@/components/ui/badge';
import Button from '@/components/ui/button/Button.vue';
import {
    Command,
    CommandEmpty,
    CommandInput,
    CommandList,
} from '@/components/ui/command';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import { XIcon } from 'lucide-vue-next';
import { computed, nextTick, ref, watch, watchEffect } from 'vue';

interface Item {
    id: number | string;
    [key: string]: any;
}

const props = defineProps<{
    items: Item[];
    selected: Array<number | string>;
    placeholder?: string;
    width?: string;
    label: string;
}>();

const emit = defineEmits<{
    (e: 'update:selected', value: Array<number | string>): void;
}>();

const open = ref(false);
const searchTerm = ref('');
const selectedIds = ref([...props.selected]);
const triggerRef = ref<HTMLElement | null>(null);
const popoverWidth = ref('300px');

watchEffect(() => {
    selectedIds.value = [...props.selected];
});

watch(open, async (val) => {
    if (val && triggerRef.value) {
        await nextTick();
        const triggerEl =
            triggerRef.value instanceof HTMLElement
                ? triggerRef.value
                : triggerRef.value?.$el;
        if (triggerEl) {
            popoverWidth.value = `${triggerEl.offsetWidth}px`;
        }
    }
});

const filteredItems = computed(() => {
    const term = searchTerm.value.trim().toLowerCase();
    if (!term) return props.items;
    return props.items.filter((item) =>
        getLabel(item).toLowerCase().includes(term),
    );
});

const selectedItems = computed(() =>
    props.items.filter((item) => selectedIds.value.includes(item.id)),
);

const getLabel = (item: Item) => item[props.label] ?? '';

const isSelected = (id: number | string) => selectedIds.value.includes(id);

const toggleItem = (id: number | string) => {
    const updated = isSelected(id)
        ? selectedIds.value.filter((itemId) => itemId !== id)
        : [...selectedIds.value, id];

    selectedIds.value = updated;
    emit('update:selected', updated);
};

const removeItem = (id: number | string) => {
    selectedIds.value = selectedIds.value.filter((itemId) => itemId !== id);
    emit('update:selected', selectedIds.value);
};
</script>

<template>
    <Popover v-model:open="open">
        <PopoverTrigger as-child>
            <Button
                ref="triggerRef"
                variant="outline"
                class="h-auto min-h-10 flex-wrap justify-start"
                :style="{ width: props.width || '300px' }"
                aria-haspopup="listbox"
                aria-expanded="open"
            >
                <span v-if="selectedItems.length === 0">
                    {{ placeholder || 'Select items...' }}
                </span>
                <div v-else class="flex flex-wrap gap-1">
                    <Badge
                        v-for="item in selectedItems"
                        :key="item.id"
                        variant="secondary"
                        class="text-md flex items-center gap-1"
                    >
                        {{ getLabel(item) }}
                        <button
                            type="button"
                            class="cursor-pointer"
                            @click.stop="removeItem(item.id)"
                        >
                            <XIcon class="h-3 w-3" />
                        </button>
                    </Badge>
                </div>
            </Button>
        </PopoverTrigger>

        <PopoverContent class="p-0" :style="{ width: popoverWidth }">
            <Command :should-filter="false">
                <CommandInput v-model="searchTerm" placeholder="Search..." />

                <CommandList>
                    <div v-if="filteredItems.length > 0" class="p-2">
                        <div
                            v-for="item in filteredItems"
                            :key="item.id"
                            class="text-md mb-1 flex cursor-pointer items-center gap-2 rounded-md px-2 py-1.5 hover:bg-muted"
                            :class="{'bg-muted': isSelected(item.id)}"
                            @click="toggleItem(item.id)"
                            role="option"
                        >
                            <span>{{ getLabel(item) }}</span>
                        </div>
                    </div>
                    <CommandEmpty v-else>No results found.</CommandEmpty>
                </CommandList>
            </Command>
        </PopoverContent>
    </Popover>
</template>
