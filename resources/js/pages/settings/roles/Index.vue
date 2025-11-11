<script lang="ts" setup>
import RoleTable from '@/components/app/role/RoleTable.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import { useAuthorization } from '@/composables/useAuthorization';
import AppLayout from '@/layouts/AppLayout.vue';
import { create, index } from '@/routes/roles';
import { type BreadcrumbItem, Filters } from '@/types';
import { RoleResponse } from '@/types/roles';
import { Head } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';

defineProps<{
    roles: RoleResponse;
    filters: Filters;
}>();

const { hasPermission } = useAuthorization();

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Roles', href: index().url }];
</script>

<template>
    <Head title="Roles" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="space-y-6">
                <!-- Heading -->
                <HeadingSmall
                    title="Roles"
                    description="Manage roles and their permissions."
                >
                    <Button
                        v-if="hasPermission('create roles')"
                        as="a"
                        :href="create().url"
                        class="cursor-pointer"
                    >
                        <span>Create</span>
                        <Plus class="h-4 w-4" />
                    </Button>
                </HeadingSmall>

                <!-- Table -->
                <RoleTable :roles="roles" :my-filters="filters" />
            </div>
        </div>
    </AppLayout>
</template>
