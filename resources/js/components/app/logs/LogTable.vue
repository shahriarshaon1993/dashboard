<script setup lang="ts">
import { Filters, SharedData } from '@/types';
import { Log, LogResponse } from '@/types/logs';
import { computed, ref } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { useAuthorization } from '@/composables/useAuthorization';
import { index, destroy, bulkDestroy, clearHistory } from '@/routes/activities';
import { debounce } from 'lodash';
import { toast } from 'vue-sonner';
import { Input } from '@/components/ui/input';
import { Search, Trash2, Check } from 'lucide-vue-next';
import { Table, TableBody, TableHead, TableHeader, TableRow, TableCell } from '@/components/ui/table';
import { Checkbox } from '@/components/ui/checkbox';
import SortTableHeader from '@/components/app/SortableHeader.vue';
import { getSerial } from '@/lib/utils';
import DropdownAction from '@/components/app/DropdownAction.vue';
import { DropdownMenuItem, DropdownMenuSeparator } from '@/components/ui/dropdown-menu';
import TableDataNotFound from '@/components/app/TableDataNotFound.vue';
import RowsPerPageSelect from '@/components/app/RowsPerPageSelect.vue';
import TablePagination from '@/components/app/TablePagination.vue';
import DeleteButton from '@/components/app/DeleteButton.vue';
import { Button } from '@/components/ui/button';
import AppSheet from '@/components/app/AppSheet.vue';
import AppDialog from '@/components/app/AppDialog.vue';

interface TimeOption {
    label: string
    value: string
}

const props = defineProps<{
    logs: LogResponse;
    myFilters: Filters;
}>();

const isConfirmOpen = ref(false);
const isDeleteLoading = ref(false);
const isBulkConfirmOpen = ref(false);
const isBulkDeleteLoading = ref(false);
const logId = ref<number | null>(null);

const isConfirmClearLog = ref<boolean>(false);
const isClearLogLoading = ref<boolean>(false);

const page = usePage<SharedData>();
const { hasPermission, hasPermissions } = useAuthorization();

const activity = ref<Log>();
const openDetails = ref<boolean>(false);
const selectedRows = ref<number[]>([]);
const filters = ref<Filters>(props.myFilters);

const form = useForm({
    range: '1h',
});

const sensitiveKeys = [
    "id",
    "password",
    "remember_token",
    "email_verified_at",
    "two_factor_secret",
    "two_factor_confirmed_at",
    "two_factor_recovery_codes"
];

const options = ref<TimeOption[]>([
    { label: 'Last 15min', value: '15m' },
    { label: 'Last 1 hour', value: '1h' },
    { label: 'Last 24 hours', value: '24h' },
    { label: 'Last 7 days', value: '7d' },
    { label: 'Last 4 weeks', value: '4w' },
    { label: 'All times', value: 'all' }
]);

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
    logId.value = id;
    isConfirmOpen.value = true;
};

const confirmBulkDelete = () => (isBulkConfirmOpen.value = true);

const handleDelete = () => {
    isDeleteLoading.value = true;
    router.delete(destroy(logId.value!), {
        preserveState: true,
        onSuccess: () => {
            logId.value = null;
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
        selectedRows.value = props.logs.data.map((log: any) => log.id);
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

const selectAll = computed({
    get: () => selectedRows.value.length === props.logs.data.length,
    set: (value) => toggleSelectAll(value),
});

const handleSubmit = () => openDetails.value = false;

const viewDetails = (id: number) => {
    activity.value = props.logs.data.find(
        log => log.id === id
    );

    openDetails.value = true;
};

const formattedOldValue = computed(() => {
    if (!activity.value?.old) return "";

    const data = { ...activity.value?.old };
    sensitiveKeys.forEach(key => {
        if (key in data) delete data[key];
    });

    return Object.values(data)
        .map(value => `${value}`)
        .join("\n");
});

const formattedChangesValue = computed(() => {
    if (!activity.value?.changes) return "";

    const data = { ...activity.value?.changes };
    sensitiveKeys.forEach(key => {
        if (key in data) delete data[key];
    });

    return Object.values(data)
        .map(value => `${value}`)
        .join("\n");
});

const confirmClearLog = () => (isConfirmClearLog.value = true);

const clearActivityLogHistory = (): void => {
    isConfirmClearLog.value = false;
    isClearLogLoading.value = true;

    toast.promise<string>(
        new Promise<string>((resolve, reject) => {
            form.delete(clearHistory().url, {
                preserveState: true,
                onSuccess: (page: any) => {
                    const message = page.props.flash?.success ?? "Success";
                    resolve(message);
                    isClearLogLoading.value = false;
                },
                onError: (errors: any) => {
                    isClearLogLoading.value = false;

                    const message = errors.range ?? "Something went wrong";

                    reject(message);
                }
            });
        }),
        {
            loading: "Loading...",
            success: (msg: string) => msg,
            error: (err: any) => err,
        }
    );
};
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
            <span class="absolute inset-y-0 start-0 flex items-center justify-center px-2">
                <Search class="size-4 text-muted-foreground" />
            </span>
        </div>

        <div class="flex items-center space-x-2">
            <Button
                v-if="hasPermission('delete activity')"
                class="cursor-pointer"
                @click="confirmClearLog"
            >
                Clear Logs
            </Button>

            <Button
                v-if="hasPermission('delete activity')"
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
        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead v-if="hasPermission('delete activity')" class="w-6">
                        <Checkbox :model-value="selectAll" @update:model-value="toggleSelectAll((selectAll = !selectAll))"/>
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
                        label="Log name"
                        sort-key="log_name"
                        :active-sort-key="filters.sort_field"
                        :sort-order="filters.sort_order"
                        @sort="sort"
                    />
                    <SortTableHeader
                        label="Description"
                        sort-key="description"
                        :active-sort-key="filters.sort_field"
                        :sort-order="filters.sort_order"
                        @sort="sort"
                    />
                    <TableHead>User</TableHead>
                    <TableHead class="text-center">IP</TableHead>
                    <SortTableHeader
                        label="Date"
                        sort-key="created_at"
                        :active-sort-key="filters.sort_field"
                        :sort-order="filters.sort_order"
                        @sort="sort"
                    />
                    <TableHead v-if="hasPermissions(['delete activity'])" class="text-right">
                        Actions
                    </TableHead>
                </TableRow>
            </TableHeader>

            <TableBody>
                <template v-if="logs.data.length > 0">
                    <TableRow
                        v-for="(log, index) in logs.data"
                        :key="log.id"
                        @click="viewDetails(log.id)"
                        class="cursor-pointer"
                    >
                        <TableCell v-if="hasPermissions(['delete activity', 'export activity'])" @click.stop>
                            <Checkbox :model-value="selectedRows.includes(log.id)" @update:model-value="toggleRow(log.id)" />
                        </TableCell>
                        <TableCell class="text-center">
                            {{ getSerial(index, logs.meta) }}
                        </TableCell>
                        <TableCell>{{ log.log_name }}</TableCell>
                        <TableCell>{{ log.description }}</TableCell>
                        <TableCell>{{log.causer?.name ?? 'System' }}</TableCell>
                        <TableCell class="text-center">{{ log.ip }}</TableCell>
                        <TableCell>{{ log.created_at }}</TableCell>
                        <TableCell v-if="hasPermissions(['show activity', 'delete activity'])" class="text-right" @click.stop>
                            <DropdownAction>
                                <DropdownMenuItem
                                    v-if="hasPermission('delete activity')"
                                    @click="confirmDelete(log.id)"
                                >
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

    <!-- Pagination -->
    <div class="flex items-center justify-between px-2">
        <div class="flex-1 text-sm text-muted-foreground">
            {{ selectedRows.length }} of
            {{ logs.meta.total }}
            row(s) selected.
        </div>

        <div class="flex items-center space-x-6 lg:space-x-8">
            <RowsPerPageSelect
                label="Per Page"
                v-model="filters.per_page"
                @update:model-value="updatePerPage"
            />

            <TablePagination
                :current-page="logs.meta.current_page"
                :last-page="logs.meta.last_page"
                :total="logs.meta.total"
                :per-page="logs.meta.per_page"
                :links="logs.meta.links"
                @page-change="changePage"
            />
        </div>
    </div>

    <!-- Show details sheet -->
    <AppSheet v-model="openDetails" title="Activity details" @submit="handleSubmit">
        <!-- Content -->
        <div class="mt-4">
            <div class="pb-2">
                <h4 class="font-semibold">Log is {{ activity?.log_name }}</h4>
                <div class="mt-2 flex items-center gap-2">
                    <p>IP: {{ activity?.ip }}</p>
                    <p>Date: {{ activity?.created_at }}</p>
                </div>
                <p class="max-w-3xl">{{ activity?.user_agent }}</p>
            </div>
            <DropdownMenuSeparator />
            <div class="space-y-2">
                <p>{{ activity?.description }}</p>

                <div class="mt-2 flex w-full items-center gap-2">
                    <div v-if="activity?.changes" class="flex-1 space-y-2">
                        <h4>Change:</h4>
                        <div class="p-4 border rounded bg-gray-100">
                            <pre>{{ formattedChangesValue }}</pre>
                        </div>
                    </div>

                    <div v-if="activity?.old" class="flex-1 space-y-2">
                        <h4>Old:</h4>
                        <div class="p-4 border rounded bg-gray-100">
                            <pre>{{ formattedOldValue }}</pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppSheet>

    <!-- Confirmation Dialogs -->
    <DeleteButton v-model="isConfirmOpen" :is-processing="isDeleteLoading" @on-destroy="handleDelete"/>
    <DeleteButton v-model="isBulkConfirmOpen" :is-processing="isBulkDeleteLoading" @on-destroy="handleBulkDelete" />

    <!-- Clear Activity Log Dialogs -->
    <AppDialog
        v-model="isConfirmClearLog"
        title="Delete activity log data"
        :is-processing="isClearLogLoading"
        @submit="clearActivityLogHistory"
    >
        <div class="inline-flex flex-wrap gap-2">
            <div v-for="(item, index) in options" :key="index" class="inline-block">
                <input type="radio" :id="'opt-' + index" :value="item.value" v-model="form.range" class="peer hidden"/>

                <label
                    :for="'opt-' + index"
                    class="cursor-pointer hover:bg-accent/50 flex items-start gap-3 rounded-lg border px-3 py-2 peer-checked:border-blue-200 peer-checked:bg-blue-200"
                >
                    <div class="flex items-center gap-2">
                        <Check v-if="form.range === item.value" class="h-3 w-3" />
                        <p class="text-sm">{{ item.label }}</p>
                    </div>
                </label>
            </div>
        </div>
    </AppDialog>
</template>
