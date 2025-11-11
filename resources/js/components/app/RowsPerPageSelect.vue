<script setup lang="ts">
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { computed } from 'vue';

interface Props {
    modelValue?: number;
    label?: string;
    options?: number[];
    triggerClass?: string;
}

const props = withDefaults(defineProps<Props>(), {
    modelValue: 15,
    label: 'Rows per page',
    options: () => [15, 25, 50, 100],
    triggerClass: 'w-[80px]',
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: number): void;
}>();

const internalModelValue = computed({
    get: () => props.modelValue,
    set: (value: string | number) => {
        const parsedValue =
            typeof value === 'string' ? parseInt(value, 10) : value;
        emit('update:modelValue', parsedValue);
    },
});

const resolvedOptions = computed(() => props.options);

const handleUpdate = (value: string | number) => {
    const parsedValue = typeof value === 'string' ? parseInt(value, 10) : value;
    emit('update:modelValue', parsedValue);
};
</script>

<template>
    <div class="flex items-center space-x-2">
        <p class="text-sm font-medium">{{ label }}</p>
        <Select v-model="internalModelValue" @update:model-value="handleUpdate">
            <SelectTrigger :class="triggerClass">
                <SelectValue :placeholder="String(internalModelValue)" />
            </SelectTrigger>
            <SelectContent>
                <SelectItem
                    v-for="option in resolvedOptions"
                    :key="option"
                    :value="String(option)"
                >
                    {{ option }}
                </SelectItem>
            </SelectContent>
        </Select>
    </div>
</template>
