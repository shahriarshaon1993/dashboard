<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import UserForm from '@/components/app/user/UserForm.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { create, index } from '@/routes/users';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Users', href: index().url },
    { title: 'Create', href: create().url },
];
</script>

<template>
    <Head title="Create Role" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="space-y-6">
                <HeadingSmall title="Create User" description="Create a new user and assign roles with permissions.">
                    <Button variant="link" as="a" :href="index().url" class="cursor-pointer">
                        <span>Back to Users</span>
                    </Button>
                </HeadingSmall>

                <!-- Form -->
                <UserForm />
            </div>
        </div>
    </AppLayout>
</template>
