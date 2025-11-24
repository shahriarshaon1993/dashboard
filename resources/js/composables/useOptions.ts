import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { ModuleWithPermissions, OptionItem, SelectOption, SharedData } from '@/types';

export function useOptions() {
    const page = usePage<SharedData>();

    const options = computed(() => page.props.options ?? {
        roles: [],
        modules: [],
        activeStatus: []
    });

    const roles = computed<OptionItem[]>(() => options.value.roles ?? []);
    const modules = computed<ModuleWithPermissions[]>(() => options.value.modules ?? []);
    const activeStatus = computed<SelectOption[]>(() => options.value.activeStatus ?? []);

    return {
        roles,
        modules,
        activeStatus
    }
}
