<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import RoleForm from '@/components/app/role/RoleForm.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { create, index } from '@/routes/roles';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Roles', href: index().url },
    { title: 'Create', href: create().url },
];
</script>

<template>
    <Head title="Create Role" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="space-y-6">
                <!-- Heading -->
                <HeadingSmall
                    title="Create Role"
                    description="Create a new role and assign permissions."
                >
                    <Button
                        variant="link"
                        as="a"
                        :href="index().url"
                        class="cursor-pointer"
                    >
                        <span>Back to Roles</span>
                    </Button>
                </HeadingSmall>

                <!-- Form -->
                <RoleForm />
            </div>
        </div>
    </AppLayout>
</template>
