<script lang="ts" setup>
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { computed } from 'vue';

const props = defineProps<{
    modelValue: boolean;
    title?: string;
    description?: string;
    isProcessing?: boolean;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: boolean): void;
    (e: 'submit'): void;
}>();

const isOpen = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value),
});

const handleSubmit = () => emit('submit');

const closeDialog = () => (isOpen.value = false);
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>{{title}}</DialogTitle>
                <DialogDescription>{{description}}</DialogDescription>
            </DialogHeader>

            <slot />

            <DialogFooter>
                <Button variant="outline" @click="closeDialog" class="cursor-pointer">
                    Cancel
                </Button>
                <Button variant="destructive" @click="handleSubmit" class="cursor-pointer" :disabled="props.isProcessing">
                    {{ props.isProcessing ? 'Deleting...' : 'Confirm' }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
