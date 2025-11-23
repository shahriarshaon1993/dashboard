<script setup lang="ts">
// import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { index as viewRoles } from '@/routes/roles';
import { index as viewUsers } from '@/routes/users';
import { index as viewActivity } from '@/routes/activities';
import { edit as editGeneralSetting } from '@/routes/general-settings';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { LayoutGrid, Settings, Users, ClipboardPenLine } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        permissions: ['access dashboard'],
        icon: LayoutGrid,
    },
    {
        title: 'Users',
        href: viewUsers(),
        permissions: ['view users'],
        icon: Users,
    },
    {
        title: 'System Settings',
        href: '',
        permissions: ['view roles', 'access settings'],
        icon: Settings,
        items: [
            {
                title: 'General Setting',
                href: editGeneralSetting(),
                permission: 'access settings'
            },
            {
                title: 'Roles & Permissions',
                href: viewRoles(),
                permission: 'view roles',
            },
        ],
    },
    {
        title: 'System Activity Logs',
        href: viewActivity(),
        permissions: ['view activity', 'delete activity', 'export activity'],
        icon: ClipboardPenLine,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <!-- <NavFooter :items="footerNavItems" /> -->
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
