import type { PageProps } from '@inertiajs/core';
import { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface SidebarNavItem {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon;
    permission: string;
}

export interface SubMenus {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    isActive?: boolean;
    permission: string;
}

export interface NavItem {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon;
    items?: SubMenus[];
    permissions: string | string[];
}

export type AppPageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    sidebarOpen: boolean;
};

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    roles: string[];
    permissions: string[];
}

export interface PaginationLinks {
    first: string;
    last: string;
    prev: string | null;
    next: string | null;
}

export interface PaginationMetaLink {
    url: string | null;
    label: string;
    active: boolean;
}

export interface PaginationMeta {
    current_page: number;
    from: number;
    last_page: number;
    links: PaginationMetaLink[];
    path: string;
    per_page: number;
    to: number;
    total: number;
}

export interface Filters {
    search: string;
    per_page: number;
    sort_field: string;
    sort_order: 'asc' | 'desc';
}

export interface OptionItem {
    id: number
    name: string
}

export interface SelectOption {
    value: string
    label: string
}

export interface SharedData extends PageProps {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    flash: { message: string; success: string; error: string };
    sidebarOpen: boolean;
    settings: {
        site_title: string;
        timezone: string;
        date_format: string;
        site_logo: string;
    };
    options: {
        roles: OptionItem[];
        modules: ModuleWithPermissions[];
        activeStatus: SelectOption[];
    }
}

export interface Permission {
    id: number;
    module_id: number;
    title: string;
    name: string;
    guard_name: string;
    created_at: string;
    updated_at: string;
}

export interface ModuleWithPermissions {
    id: number;
    name: string;
    description: string;
    permissions: Permission[];
}

export type BreadcrumbItemType = BreadcrumbItem;
