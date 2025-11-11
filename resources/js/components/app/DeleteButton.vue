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
    isProcessing?: boolean;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: boolean): void;
    (e: 'onDestroy'): void;
}>();

const isOpen = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value),
});

const handleDestroy = () => emit('onDestroy');

const closeDialog = () => (isOpen.value = false);
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>Are you sure?</DialogTitle>
                <DialogDescription>
                    This action cannot be undone. It will permanently delete the
                    item.
                </DialogDescription>
            </DialogHeader>

            <DialogFooter>
                <Button
                    variant="outline"
                    @click="closeDialog"
                    class="cursor-pointer"
                    >Cancel</Button
                >
                <Button
                    variant="destructive"
                    @click="handleDestroy"
                    class="cursor-pointer"
                    :disabled="props.isProcessing"
                >
                    {{ props.isProcessing ? 'Deleting...' : 'Confirm' }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
