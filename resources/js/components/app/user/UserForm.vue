<script setup lang="ts">
import FileUploader from '@/components/app/FileUploader.vue';
import InputError from '@/components/InputError.vue';
import MultipleSelection from '@/components/InputMultipleSelect.vue';
import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from '@/components/ui/accordion';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { store, update } from '@/routes/users';
import { SharedData } from '@/types';
import { User, UserForm } from '@/types/users';
import { useForm, usePage } from '@inertiajs/vue3';
import { startCase } from 'lodash';
import { toast } from 'vue-sonner';
import { useOptions } from '@/composables/useOptions';

const props = defineProps<{
    user?: User,
}>();

const page = usePage<SharedData>();
const { roles, modules, activeStatus } = useOptions();

const form = useForm<UserForm>({
    name: props.user?.name ?? '',
    email: props.user?.email ?? '',
    phone: props.user?.phone ?? '',
    password: '',
    avatar: null,
    is_active: props.user?.is_active ?? null,
    roles: props.user?.roles?.map((r: any) => r.id) ?? [],
    permissions: props.user?.permissions?.map((p: any) => p.id) ?? [],
    avatar_removed: false,
});

const submit = () => {
    if (props.user?.id) {
        form.submit('post', update(props.user?.id).url, {
            preserveScroll: true,
            forceFormData: true,
            headers: {
                'X-HTTP-Method-Override': 'PATCH',
            },
            onSuccess: () => {
                toast('Success!', { description: page.props.flash.success });
            }
        });
    } else {
        form.post(store().url, {
            preserveScroll: true,
            onSuccess: () => {
                toast('Success!', { description: page.props.flash.success });
            },
        });
    }
};
</script>

<template>
    <div class="w-full md:max-w-4xl">
        <div class="w-full space-y-12">
            <form class="space-y-6" @submit.prevent="submit">
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <Label for="name">Name</Label>
                        <div class="mt-2">
                            <Input id="name" v-model="form.name" class="mt-1 block w-full" placeholder="User name" />
                        </div>
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="sm:col-span-3">
                        <Label for="email">Email</Label>
                        <div class="mt-2">
                            <Input id="email" type="email" v-model="form.email" class="mt-1 block w-full" placeholder="User email" />
                        </div>
                        <InputError :message="form.errors.email" />
                    </div>

                    <div class="sm:col-span-3">
                        <Label for="phone">Phone number</Label>
                        <div class="mt-2">
                            <Input id="phone" v-model="form.phone" class="mt-1 block w-full" placeholder="User phone"/>
                        </div>
                        <InputError :message="form.errors.phone" />
                    </div>

                    <div class="sm:col-span-3">
                        <Label for="password">Password</Label>
                        <div class="mt-2">
                            <Input id="password" type="password" v-model="form.password" class="mt-1 block w-full" placeholder="Enter password" />
                        </div>
                        <InputError :message="form.errors.password" />
                    </div>

                    <div class="sm:col-span-3">
                        <Label for="is_active">Status: (Active/Inactive)</Label>
                        <div class="mt-2">
                            <Select v-model="form.is_active">
                                <SelectTrigger>
                                    <SelectValue :placeholder="'Select status'"/>
                                </SelectTrigger>

                                <SelectContent>
                                    <SelectItem v-for="status in activeStatus" :key="status.value" :value="status.value">
                                        {{ status.label }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                        <InputError :message="form.errors.is_active" />
                    </div>

                    <div class="col-span-full">
                        <Label for="avatar">Add picture</Label>
                        <div class="mt-2">
                            <FileUploader
                                :key="user?.id"
                                :multiple="false"
                                :maxSizeMB="2"
                                accept="image/*"
                                :initial-urls="user?.avatar ? [user?.avatar] : []"
                                @update:files="(f) => (form.avatar = f[0] ?? null)"
                                @initial-removed="form.avatar_removed = true"
                            />
                        </div>
                        <InputError :message="form.errors.avatar" />
                    </div>

                    <div class="col-span-full">
                        <div class="space-y-2">
                            <Label for="avatar">Assign to roles</Label>
                            <div class="mt-2">
                                <MultipleSelection width="100%" label="name" :items="roles" v-model:selected="form.roles" placeholder="Select roles" />
                            </div>
                            <InputError :message="form.errors.roles" />
                        </div>
                    </div>

                    <div class="col-span-full">
                        <Label for="avatar">Assign to permission</Label>
                        <Accordion type="single" class="mt-2 w-full rounded-lg border px-4 py-2" collapsible>
                            <AccordionItem value="null" class="border-none">
                                <AccordionTrigger class="cursor-pointer hover:no-underline">
                                    <div class="text-left">
                                        <div>System permissions</div>
                                        <p class="text-sm text-muted-foreground">
                                            Role permissions permit access to
                                            resources under your system.
                                        </p>
                                    </div>
                                </AccordionTrigger>
                                <AccordionContent>
                                    <div
                                        v-for="(module, index) in modules"
                                        :key="index"
                                        class="flex items-center justify-between border-b p-3 last:border-none"
                                    >
                                        <div>
                                            <h4 class="font-semibold">
                                                {{ startCase(module.name) }}
                                            </h4>
                                            <p class="text-sm text-muted-foreground">
                                                {{ module.description }}
                                            </p>
                                        </div>

                                        <div>
                                            <MultipleSelection
                                                width="240px"
                                                label="name"
                                                :items="module.permissions"
                                                v-model:selected="form.permissions"
                                                placeholder="Select roles"
                                            />
                                        </div>
                                    </div>
                                </AccordionContent>
                            </AccordionItem>
                        </Accordion>
                        <InputError :message="form.errors.permissions" />
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <Button :disabled="form.processing" class="cursor-pointer">
                        Save
                    </Button>
                </div>
            </form>
        </div>
    </div>
</template>
