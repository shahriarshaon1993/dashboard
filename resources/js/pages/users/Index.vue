<script lang="ts" setup>
import UserTable from '@/components/app/user/UserTable.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import { useAuthorization } from '@/composables/useAuthorization';
import AppLayout from '@/layouts/AppLayout.vue';
import { create, index } from '@/routes/users';
import { type BreadcrumbItem, Filters } from '@/types';
import { UserResponse } from '@/types/users';
import { Head } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';

defineProps<{
    users: UserResponse;
    filters: Filters;
}>();

const { hasPermission } = useAuthorization();

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Users', href: index().url }];
</script>

<template>
    <Head title="Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="space-y-6">
                <!-- Heading -->
                <HeadingSmall
                    title="Users"
                    description="Manage users and their permissions."
                >
                    <Button
                        v-if="hasPermission('create users')"
                        as="a"
                        :href="create().url"
                        class="cursor-pointer"
                    >
                        <span>Create</span>
                        <Plus class="h-4 w-4" />
                    </Button>
                </HeadingSmall>

                <!-- Table -->
                <UserTable :users="users" :my-filters="filters"/>
            </div>
        </div>
    </AppLayout>
</template>
