<script setup lang="ts">
import DeleteButton from '@/components/app/DeleteButton.vue';
import DropdownAction from '@/components/app/DropdownAction.vue';
import ExportButton from '@/components/app/ExportButton.vue';
import ImportButton from '@/components/app/ImportButton.vue';
import RowsPerPageSelect from '@/components/app/RowsPerPageSelect.vue';
import SortTableHeader from '@/components/app/SortableHeader.vue';
import TableDataNotFound from '@/components/app/TableDataNotFound.vue';
import TablePagination from '@/components/app/TablePagination.vue';
import Button from '@/components/ui/button/Button.vue';
import { Checkbox } from '@/components/ui/checkbox';
import {
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuSeparator,
} from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { useAuthorization } from '@/composables/useAuthorization';
import { getSerial } from '@/lib/utils';
import {
    bulkDestroy,
    destroy,
    edit,
    exportMethod,
    importMethod,
    index,
} from '@/routes/roles';
import { Filters, SharedData } from '@/types';
import { RoleResponse } from '@/types/roles';
import { Link, router, usePage } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import { Search, SquarePen, Trash2 } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { toast } from 'vue-sonner';

const props = defineProps<{
    roles: RoleResponse;
    myFilters: Filters;
}>();

const isConfirmOpen = ref(false);
const isDeleteLoading = ref(false);
const isBulkConfirmOpen = ref(false);
const isBulkDeleteLoading = ref(false);
const roleId = ref<number | null>(null);

const page = usePage<SharedData>();
const selectedRows = ref<number[]>([]);
const filters = ref<Filters>(props.myFilters);

const { hasPermission, hasPermissions } = useAuthorization();

const updateFilters = () => {
    router.get(index(), filters.value, {
        preserveState: true,
        preserveScroll: true,
    });
};

const debouncedSearch = debounce(() => {
    updateFilters();
}, 500);

const confirmDelete = (id: number) => {
    roleId.value = id;
    isConfirmOpen.value = true;
};

const confirmBulkDelete = () => (isBulkConfirmOpen.value = true);

const handleDelete = () => {
    isDeleteLoading.value = true;
    router.delete(destroy(roleId.value!), {
        preserveState: true,
        onSuccess: () => {
            roleId.value = null;
            toast('Success!', {
                description: page.props.flash.success,
            });
            isDeleteLoading.value = false;
            isConfirmOpen.value = false;
        },
    });
};

const handleBulkDelete = () => {
    isBulkDeleteLoading.value = true;
    if (selectedRows.value.length > 0) {
        router.delete(bulkDestroy(), {
            data: { ids: selectedRows.value },
            preserveState: true,
            onSuccess: () => {
                selectedRows.value = [];
                toast('Success!', {
                    description: page.props.flash.success,
                });
                isBulkDeleteLoading.value = false;
                isBulkConfirmOpen.value = false;
            },
        });
    }
};

const toggleSelectAll = (checked: boolean): void => {
    if (checked) {
        selectedRows.value = props.roles.data.map((b: any) => b.id);
    } else {
        selectedRows.value = [];
    }
};

const toggleRow = (id: number) => {
    const index = selectedRows.value.indexOf(id);

    if (index === -1) {
        selectedRows.value.push(id);
    } else {
        selectedRows.value.splice(index, 1);
    }
};

const sort = (column: string): void => {
    if (filters.value.sort_field === column) {
        filters.value.sort_order =
            filters.value.sort_order === 'asc' ? 'desc' : 'asc';
    } else {
        filters.value.sort_field = column;
        filters.value.sort_order = 'desc';
    }
    updateFilters();
};

const updatePerPage = (value: string): void => {
    filters.value.per_page = parseInt(value);
    updateFilters();
};

const changePage = (page: number) => {
    router.get(
        index(),
        { ...filters.value, page },
        {
            preserveState: true,
            preserveScroll: true,
        },
    );
};

const handleImportFinish = () => {
    toast('Success!', {
        description: 'Import completed successfully.',
    });
};

const selectAll = computed({
    get: () => selectedRows.value.length === props.roles.data.length,
    set: (value) => toggleSelectAll(value),
});
</script>

<template>
    <!-- Toolbar -->
    <div class="flex items-center justify-between space-x-2">
        <div class="relative w-full items-center lg:w-[250px]">
            <Input
                type="text"
                v-model="filters.search"
                placeholder="Search..."
                @input="debouncedSearch"
                class="pl-8"
            />
            <span
                class="absolute inset-y-0 start-0 flex items-center justify-center px-2"
            >
                <Search class="size-4 text-muted-foreground" />
            </span>
        </div>

        <div class="flex items-center space-x-2">
            <ImportButton
                v-if="hasPermission('import roles')"
                :url="importMethod().url"
                label="Import"
                :on-finish="handleImportFinish"
            />

            <ExportButton
                v-if="hasPermission('export roles')"
                :url="exportMethod().url"
                :payload="{ ids: selectedRows }"
                file-name="roles.xlsx"
            />

            <Button
                v-if="hasPermission('delete roles')"
                :disabled="selectedRows.length < 1"
                variant="destructive"
                class="cursor-pointer"
                @click="confirmBulkDelete"
            >
                <Trash2 class="h-4 w-4" />
                ({{ selectedRows.length }})
            </Button>
        </div>
    </div>

    <!-- Table -->
    <div class="rounded-md border">
        <div class="relative overflow-auto">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead
                            v-if="hasPermission('delete roles')"
                            class="w-6"
                        >
                            <Checkbox
                                :model-value="selectAll"
                                @update:model-value="
                                    toggleSelectAll((selectAll = !selectAll))
                                "
                            />
                        </TableHead>
                        <SortTableHeader
                            label="#"
                            sort-key="id"
                            :active-sort-key="filters.sort_field"
                            :sort-order="filters.sort_order"
                            @sort="sort"
                            class="w-8 text-center"
                        />
                        <SortTableHeader
                            label="Name"
                            sort-key="name"
                            :active-sort-key="filters.sort_field"
                            :sort-order="filters.sort_order"
                            @sort="sort"
                        />
                        <SortTableHeader
                            label="Guard"
                            sort-key="guard_name"
                            :active-sort-key="filters.sort_field"
                            :sort-order="filters.sort_order"
                            @sort="sort"
                        />
                        <SortTableHeader
                            label="Created at"
                            sort-key="created_at"
                            :active-sort-key="filters.sort_field"
                            :sort-order="filters.sort_order"
                            @sort="sort"
                        />
                        <SortTableHeader
                            label="Updated at"
                            sort-key="updated_at"
                            :active-sort-key="filters.sort_field"
                            :sort-order="filters.sort_order"
                            @sort="sort"
                        />
                        <TableHead
                            v-if="
                                hasPermissions(['update roles', 'delete roles'])
                            "
                            class="text-right"
                        >
                            Actions
                        </TableHead>
                    </TableRow>
                </TableHeader>

                <TableBody>
                    <template v-if="roles.data.length > 0">
                        <TableRow
                            v-for="(role, index) in roles.data"
                            @click="toggleRow(role.id)"
                            :key="role.id"
                            class="cursor-pointer"
                        >
                            <TableCell
                                v-if="hasPermissions(['delete roles', 'export roles'])"
                                @click.stop
                            >
                                <Checkbox
                                    :model-value="selectedRows.includes(role.id)"
                                    @update:model-value="toggleRow(role.id)"
                                />
                            </TableCell>
                            <TableCell class="text-center">
                                {{ getSerial(index, roles.meta) }}
                            </TableCell>
                            <TableCell class="text-left">
                                {{ role.name }}
                            </TableCell>
                            <TableCell class="text-left">
                                {{ role.guard_name }}
                            </TableCell>
                            <TableCell>
                                {{ role.created_at }}
                            </TableCell>
                            <TableCell>
                                {{ role.updated_at }}
                            </TableCell>

                            <TableCell
                                v-if="hasPermissions(['update roles', 'delete roles'])"
                                class="text-right"
                                @click.stop
                            >
                                <DropdownAction>
                                    <DropdownMenuGroup>
                                        <DropdownMenuItem v-if="hasPermission('update roles')">
                                            <Link class="w-full inline-flex items-center gap-2" :href="edit(role.id)">
                                                <SquarePen class="mr-2 h-4 w-4"/>
                                                Edit
                                            </Link>
                                        </DropdownMenuItem>
                                    </DropdownMenuGroup>
                                    <DropdownMenuSeparator />
                                    <DropdownMenuItem v-if="hasPermission('delete roles')" @click="confirmDelete(role.id)" >
                                        <Trash2 class="mr-2 h-4 w-4" />
                                        <span>Delete</span>
                                    </DropdownMenuItem>
                                </DropdownAction>
                            </TableCell>
                        </TableRow>
                    </template>

                    <TableDataNotFound v-else :length="6" />
                </TableBody>
            </Table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-between px-2">
        <div class="flex-1 text-sm text-muted-foreground">
            {{ selectedRows.length }} of
            {{ roles.meta.total }}
            row(s) selected.
        </div>

        <div class="flex items-center space-x-6 lg:space-x-8">
            <RowsPerPageSelect
                label="Per Page"
                v-model="filters.per_page"
                @update:model-value="updatePerPage"
            />

            <TablePagination
                :current-page="roles.meta.current_page"
                :last-page="roles.meta.last_page"
                :total="roles.meta.total"
                :per-page="roles.meta.per_page"
                :links="roles.meta.links"
                @page-change="changePage"
            />
        </div>
    </div>

    <!-- Confirmation Dialog -->
    <DeleteButton
        v-model="isConfirmOpen"
        :is-processing="isDeleteLoading"
        @on-destroy="handleDelete"
    />
    <DeleteButton
        v-model="isBulkConfirmOpen"
        :is-processing="isBulkDeleteLoading"
        @on-destroy="handleBulkDelete"
    />
</template>
