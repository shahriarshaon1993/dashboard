<script setup lang="ts">
import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from '@/components/ui/collapsible';
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
} from '@/components/ui/sidebar';
import { useAuthorization } from '@/composables/useAuthorization';
import { urlIsActive } from '@/lib/utils';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { ChevronRight } from 'lucide-vue-next';

defineProps<{
    items: NavItem[];
}>();

const page = usePage();
const { hasPermission, hasPermissions } = useAuthorization();
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel>Platform</SidebarGroupLabel>
        <SidebarMenu>
            <Collapsible
                v-for="item in items"
                :key="item.title"
                :default-open="urlIsActive(item.href, page.url)"
                as-child
                class="group/collapsible"
            >
                <SidebarMenuItem>
                    <template v-if="item.items">
                        <CollapsibleTrigger
                            v-if="hasPermissions(item.permissions)"
                            as-child
                        >
                            <SidebarMenuButton>
                                <component v-if="item.icon" :is="item.icon" />
                                <span class="font-medium">
                                    {{ item.title }}
                                </span>
                                <ChevronRight
                                    class="ml-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90"
                                />
                            </SidebarMenuButton>
                        </CollapsibleTrigger>

                        <CollapsibleContent>
                            <SidebarMenuSub>
                                <SidebarMenuSubItem
                                    v-for="subItem in item.items"
                                    :key="subItem.title"
                                >
                                    <SidebarMenuSubButton
                                        v-if="hasPermission(subItem.permission)"
                                        :is-active="
                                            urlIsActive(subItem.href, page.url)
                                        "
                                        as-child
                                    >
                                        <Link
                                            :href="subItem.href"
                                            class="font-medium"
                                        >
                                            <span>{{ subItem.title }}</span>
                                        </Link>
                                    </SidebarMenuSubButton>
                                </SidebarMenuSubItem>
                            </SidebarMenuSub>
                        </CollapsibleContent>
                    </template>

                    <template v-else>
                        <SidebarMenuButton
                            v-if="hasPermissions(item.permissions)"
                            :is-active="urlIsActive(item.href, page.url)"
                            as-child
                        >
                            <Link :href="item.href" class="font-medium">
                                <component :is="item.icon" />
                                <span>{{ item.title }}</span>
                            </Link>
                        </SidebarMenuButton>
                    </template>
                </SidebarMenuItem>
            </Collapsible>
        </SidebarMenu>
    </SidebarGroup>
</template>
