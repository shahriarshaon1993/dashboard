<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { router } from '@inertiajs/vue3';
import { Upload } from 'lucide-vue-next';
import { ref } from 'vue';

interface Props {
    url: string;
    label?: string;
    onFinish?: () => void;
}

const props = defineProps<Props>();
const importLoading = ref(false);
const file = ref<File | null>(null);

const handleImportFile = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files?.length) {
        file.value = target.files[0];
        importFile();
    }
};

const importFile = async () => {
    if (!file.value) return;

    importLoading.value = true;
    const formData = new FormData();
    formData.append('file', file.value);

    router.post(props.url, formData, {
        preserveState: true,
        onFinish: () => {
            importLoading.value = false;
            file.value = null;
            props.onFinish?.();
        },
    });
};
</script>

<template>
    <form @submit.prevent="importFile">
        <Button
            variant="outline"
            type="submit"
            :disabled="importLoading"
            class="relative cursor-pointer overflow-hidden"
        >
            <!-- Hidden File Input -->
            <input
                type="file"
                accept=".xlsx,.csv"
                @change="handleImportFile"
                class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
            />
            {{ importLoading ? 'Importing...' : (props.label ?? 'Import') }}
            <Upload class="h-4 w-4" />
        </Button>
    </form>
</template>
