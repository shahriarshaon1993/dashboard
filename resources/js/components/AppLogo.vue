<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';

const page = usePage<SharedData>();

const setting = page.props.settings;
</script>

<template>
    <div class="flex aspect-square size-8 items-center justify-center rounded-md bg-sidebar-primary text-sidebar-primary-foreground">
        <img
            v-if="setting.site_logo"
            :src="setting.site_logo"
            :alt="setting.site_title"
            class="rounded-md"
        >
        <AppLogoIcon v-else class="size-5 fill-current text-white dark:text-black" />
    </div>

    <div class="ml-1 grid flex-1 text-left text-sm">
        <span class="mb-0.5 truncate leading-tight font-semibold">
            {{ setting.site_title }}
        </span>
    </div>
</template>
