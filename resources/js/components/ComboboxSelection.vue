<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Combobox,
    ComboboxAnchor,
    ComboboxEmpty,
    ComboboxGroup,
    ComboboxInput,
    ComboboxItem,
    ComboboxItemIndicator,
    ComboboxList,
    ComboboxTrigger,
} from '@/components/ui/combobox';
import { cn } from '@/lib/utils';
import { Check, ChevronsUpDown, Search } from 'lucide-vue-next';
import { computed, ref } from 'vue';

interface Option {
    value: string;
    label: string;
}

const props = defineProps<{
    modelValue?: Option | null;
    options: Option[];
    placeholder?: string;
}>();

const emit = defineEmits(['update:modelValue']);

const internalValue = ref<Option | null>(props.modelValue ?? null);

const valueLabel = computed(
    () => internalValue.value?.label ?? props.placeholder ?? 'Select option',
);

function updateValue(option: Option) {
    internalValue.value = option;
    emit('update:modelValue', option);
}
</script>

<template>
    <div class="w-full">
        <Combobox v-model="internalValue" by="label">
            <ComboboxAnchor as-child>
                <ComboboxTrigger as-child>
                    <Button variant="outline" class="w-full justify-between">
                        {{ valueLabel }}
                        <ChevronsUpDown
                            class="ml-2 h-4 w-4 shrink-0 opacity-50"
                        />
                    </Button>
                </ComboboxTrigger>
            </ComboboxAnchor>

            <ComboboxList class="w-full">
                <div class="relative w-full items-center">
                    <ComboboxInput
                        class="h-10 w-full rounded-none border-0 border-b pl-9 focus-visible:ring-0"
                        :placeholder="props.placeholder ?? 'Search...'"
                    />
                    <span
                        class="absolute inset-y-0 start-0 flex items-center justify-center px-3"
                    >
                        <Search class="size-4 text-muted-foreground" />
                    </span>
                </div>

                <ComboboxEmpty>No option found.</ComboboxEmpty>

                <ComboboxGroup>
                    <ComboboxItem
                        v-for="opt in options"
                        :key="opt.value"
                        :value="opt"
                        @click="updateValue(opt)"
                    >
                        {{ opt.label }}
                        <ComboboxItemIndicator>
                            <Check :class="cn('ml-auto h-4 w-4')" />
                        </ComboboxItemIndicator>
                    </ComboboxItem>
                </ComboboxGroup>
            </ComboboxList>
        </Combobox>
    </div>
</template>
