<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Download } from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';

interface Props {
    url: string;
    fileName?: string;
    payload?: Record<string, any>;
    label?: string;
}

const props = defineProps<Props>();

const exportLoading = ref(false);

const handleExport = async () => {
    try {
        exportLoading.value = true;

        const meta = document.querySelector('meta[name="csrf-token"]');
        const token = meta?.getAttribute('content') ?? '';

        const response = await fetch(props.url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
            },
            body: JSON.stringify(props.payload || {}),
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const blob = await response.blob();
        const link = document.createElement('a');
        link.href = window.URL.createObjectURL(blob);
        link.download = props.fileName || 'export.xlsx';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        toast('Success!', {
            description: 'Data exported successfully.',
        });
    } catch (error) {
        toast.error('Failed to export data. Please try again.');
        console.error(error);
    } finally {
        exportLoading.value = false;
    }
};
</script>

<template>
    <Button
        variant="outline"
        :disabled="exportLoading"
        @click="handleExport"
        class="cursor-pointer"
    >
        {{ exportLoading ? 'Exporting...' : (props.label ?? 'Export') }}
        <Download class="h-4 w-4" />
    </Button>
</template>
