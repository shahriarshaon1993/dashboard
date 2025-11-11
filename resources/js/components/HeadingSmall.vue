<script setup lang="ts">
interface Props {
    title: string;
    description?: string;
}

defineProps<Props>();
</script>

<template>
    <header class="flex items-center justify-between">
        <div>
            <h3 class="mb-0.5 text-base font-semibold">{{ title }}</h3>
            <p v-if="description" class="text-sm text-muted-foreground">
                {{ description }}
            </p>
        </div>

        <div>
            <slot />
        </div>
    </header>
</template>
