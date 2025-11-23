import { PaginationLinks, PaginationMeta } from '.';

export interface Log {
    id: number;
    log_name: string;
    description: string;
    cause: string[];
    ip: string;
    user_agent: string;
    changes: string[];
    old: string | null;
    created_at: string;

    [key: string]: any;
}

export interface LogResponse {
    data: Log[];
    links: PaginationLinks;
    meta: PaginationMeta;
}
