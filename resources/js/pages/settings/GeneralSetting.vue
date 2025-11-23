<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { GeneralSetting } from '@/types/generalSetting';
import { type BreadcrumbItem } from '@/types';
import { edit } from '@/routes/general-settings';
import GeneralSettingForm from '@/components/app/settings/GeneralSettingForm.vue';

defineProps < {
    setting: GeneralSetting;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Create or Update', href: edit().url },
];
</script>

<template>
    <Head title="Create or update setting" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="space-y-6">
                <!-- Heading -->
                <HeadingSmall
                    title="Create or update"
                    description="Create or update your application's general information."
                />

                <!-- Form -->
                <GeneralSettingForm :setting="setting"/>
            </div>
        </div>
    </AppLayout>
</template>
