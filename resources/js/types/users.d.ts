import { ActiveStatus, PaginationLinks, PaginationMeta } from '.';

export interface UserForm {
    name: string;
    email: string;
    phone: string;
    password: string;
    avatar: File | null;
    is_active: string | null;
    roles: string[] | number[];
    permissions: string[] | number[];
    avatar_removed?: boolean,

    [key: string]: any;
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar: string;
    status: ActiveStatus,
    created_at: string;
    updated_at: string;

    [key: string]: any;
}

export interface UserResponse {
    data: User[];
    links: PaginationLinks;
    meta: PaginationMeta;
}
