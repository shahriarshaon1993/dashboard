import { InertiaLinkProps } from '@inertiajs/vue3';
import { clsx, type ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function urlIsActive(
    urlToCheck: NonNullable<InertiaLinkProps['href']>,
    currentUrl: string,
) {
    return toUrl(urlToCheck) === currentUrl;
}

export function toUrl(href: NonNullable<InertiaLinkProps['href']>) {
    return typeof href === 'string' ? href : href?.url;
}

export function getSerial(index: number, meta: { current_page: number; per_page: number }) {
    return index + 1 + (meta.current_page - 1) * meta.per_page;
}

export function getVariant(color: string) {
    return color as 'default' | 'secondary' | 'destructive' | 'outline';
}
