<script setup lang="ts">
import { type BreadcrumbItem, Filters } from '@/types';
import { LogResponse } from '@/types/logs';
import { index } from '@/routes/activities';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';

import LogTable from '@/components/app/logs/LogTable.vue';

defineProps<{
    logs: LogResponse;
    filters: Filters;
}>();

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Activities', href: index().url }];
</script>

<template>
    <Head title="Logs" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="space-y-6">
                <!-- Heading -->
                <HeadingSmall title="Activity Logs" description="Views all activity logs."/>

                <!-- Table -->
                <LogTable :logs="logs" :my-filters="filters" />
            </div>
        </div>
    </AppLayout>
</template>
