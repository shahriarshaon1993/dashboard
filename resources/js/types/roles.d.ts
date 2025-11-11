import { PaginationLinks, PaginationMeta, Permission } from '.';

export interface Role {
    id: number;
    name: string;
    permissions: Permission[];
}

export interface Roles {
    id: number;
    name: string;
    email: string;
    created_at: string;
    updated_at: string;

    [key: string]: any; // For additional dynamic columns
}

export interface RoleForm {
    id?: number;
    name: string;
    permissions: number[];

    [key: string]: any;
}

export interface RoleResponse {
    data: Roles[];
    links: PaginationLinks;
    meta: PaginationMeta;
}
