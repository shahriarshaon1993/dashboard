<script setup lang="ts">
import { edit, update } from '@/routes/profile';
import { send } from '@/routes/verification';
import { Form, Head, Link, useForm, usePage } from '@inertiajs/vue3';

import FileUploader from '@/components/app/FileUploader.vue';
import DeleteUser from '@/components/DeleteUser.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem, SharedData } from '@/types';
import { toast } from 'vue-sonner';

interface Props {
    mustVerifyEmail: boolean;
    status?: string;
}

interface ProfileForm {
    name: string;
    email: string;
    avatar: File | null;
    avatar_removed: boolean;

    [key: string]: any;
}

defineProps<Props>();

const page = usePage<SharedData>();
const user = page.props.auth.user;

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Profile settings',
        href: edit().url,
    },
];

const form = useForm<ProfileForm>({
    name: user.name,
    email: user.email,
    avatar: null,
    avatar_removed: false,
});

const submit = () => {
    form.submit('post', update().url, {
        preserveScroll: true,
        forceFormData: true,
        headers: {
            'X-HTTP-Method-Override': 'PATCH',
        },
        onSuccess: () =>
            toast({
                title: 'Success!',
                description: page.props.flash.success,
            }),
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Profile settings" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall
                    title="Profile information"
                    description="Update your name and email address"
                />

                <Form class="space-y-6" @submit.prevent="submit">
                    <div class="grid gap-2">
                        <Label for="name">Name</Label>
                        <Input
                            id="name"
                            class="mt-1 block w-full"
                            v-model="form.name"
                            required
                            autocomplete="name"
                            placeholder="Full name"
                        />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="email">Email address</Label>
                        <Input
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
                            required
                            autocomplete="username"
                            placeholder="Email address"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="avatar">Profile picture</Label>
                        <FileUploader
                            :key="user.id"
                            :multiple="false"
                            :maxSizeMB="2"
                            accept="image/*"
                            :initial-urls="user.avatar ? [user.avatar] : []"
                            @update:files="(f) => (form.avatar = f[0] ?? null)"
                            @initial-removed="form.avatar_removed = true"
                        />
                    </div>

                    <div v-if="mustVerifyEmail && !user.email_verified_at">
                        <p class="-mt-4 text-sm text-muted-foreground">
                            Your email address is unverified.
                            <Link
                                :href="send()"
                                as="button"
                                class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500"
                            >
                                Click here to resend the verification email.
                            </Link>
                        </p>

                        <div
                            v-if="status === 'verification-link-sent'"
                            class="mt-2 text-sm font-medium text-green-600"
                        >
                            A new verification link has been sent to your email
                            address.
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <Button
                            :disabled="form.processing"
                            data-test="update-profile-button"
                            >Save</Button
                        >

                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p
                                v-show="form.recentlySuccessful"
                                class="text-sm text-neutral-600"
                            >
                                Saved.
                            </p>
                        </Transition>
                    </div>
                </Form>
            </div>

            <DeleteUser />
        </SettingsLayout>
    </AppLayout>
</template>
