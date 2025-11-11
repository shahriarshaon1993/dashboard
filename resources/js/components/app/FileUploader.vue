<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import { ref, watchEffect } from 'vue';
import { toast } from 'vue-sonner';

interface Props {
    multiple?: boolean;
    maxFiles?: number;
    maxSizeMB?: number;
    accept?: string;
    initialUrls?: string[];
}

const props = withDefaults(defineProps<Props>(), {
    multiple: false,
    maxFiles: 5,
    maxSizeMB: 5,
    accept: 'image/*',
    initialUrls: () => [],
});

const emit = defineEmits<{
    (e: 'update:files', files: File[]): void;
    (e: 'initial-removed'): void;
}>();

const files = ref<File[]>([]);
const previewUrls = ref<string[]>([]);

watchEffect(() => {
    if (props.initialUrls.length && previewUrls.value.length === 0) {
        previewUrls.value = [...props.initialUrls];
    }
});

const onFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const selectedFiles = target.files;

    if (!selectedFiles) return;

    Array.from(selectedFiles).forEach((file) => {
        if (file.size / 1024 / 1024 > props.maxSizeMB) {
            toast.error(`"$file.name" file is bigger then ${props.maxSizeMB}`);
            return;
        }

        const reader = new FileReader();

        reader.onload = () => {
            if (!props.multiple) {
                files.value = [file];
                previewUrls.value = [reader.result as string];
            } else {
                files.value.push(file);
                previewUrls.value.push(reader.result as string);
            }

            emit('update:files', files.value);
        };

        reader.readAsDataURL(file);
    });

    target.value = '';
};

const remove = (index: number) => {
    const isInitial = !files.value[index];

    files.value.splice(index, 1);
    previewUrls.value.splice(index, 1);

    if (isInitial) {
        emit('initial-removed');
    }

    emit('update:files', files.value);
};
</script>

<template>
    <div class="space-y-4">
        <Input
            id="image"
            type="file"
            :accept="accept"
            :multiple="multiple"
            @change="onFileChange"
        />

        <div v-if="previewUrls.length" class="mt-4 grid grid-cols-2 gap-4">
            <div
                v-for="(url, index) in previewUrls"
                :key="index"
                class="w-38 relative overflow-hidden rounded-lg border"
            >
                <img
                    for="image"
                    :src="url"
                    alt="Preview"
                    class="h-auto w-full"
                />
                <Button
                    type="button"
                    variant="destructive"
                    size="sm"
                    class="absolute top-1 right-1 cursor-pointer"
                    @click="remove(index)"
                >
                    ✕
                </Button>
            </div>
        </div>
    </div>
</template>
