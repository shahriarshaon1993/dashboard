import { Auth, SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';

export function useAuthorization() {
    const auth: Auth = usePage<SharedData>().props.auth;
    const isAdmin = auth.user.roles.includes('admin');

    const hasRole = (name: string) => {
        if (isAdmin) return true;

        return auth.user.roles.includes(name);
    };

    const hasPermission = (name: string) => {
        if (isAdmin) return true;

        return auth.user.permissions.includes(name);
    };

    const hasRoles = (names: any) => {
        if (isAdmin) return true;

        return auth.user.roles.some((name) => names.includes(name));
    };

    const hasPermissions = (names: any) => {
        if (isAdmin) return true;

        return auth.user.permissions.some((name) => names.includes(name));
    };

    return { hasRole, hasPermission, hasRoles, hasPermissions };
}
