<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import InputMultipleSelect from '@/components/InputMultipleSelect.vue';
import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from '@/components/ui/accordion';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import { store, update } from '@/routes/roles';
import { SharedData } from '@/types';
import { Role, RoleForm } from '@/types/roles';
import { useForm, usePage } from '@inertiajs/vue3';
import { startCase } from 'lodash';
import { toast } from 'vue-sonner';
import { useOptions } from '@/composables/useOptions';

const props = defineProps<{
    role?: Role;
}>();

const page = usePage<SharedData>();
const { modules } = useOptions();

const form = useForm<RoleForm>({
    name: props.role?.name ?? '',
    permissions: props.role?.permissions?.map((p: any) => p.id) ?? [],
});

const submit = () => {
    if (props.role?.id) {
        form.put(update(props.role?.id).url, {
            preserveScroll: true,
            onSuccess: () => {
                toast('Success!', {
                    description: page.props.flash.success,
                });
            },
        });
    } else {
        form.post(store().url, {
            preserveScroll: true,
            onSuccess: () => {
                toast('Success!', {
                    description: page.props.flash.success,
                });
            },
        });
    }
};
</script>

<template>
    <div class="w-full md:max-w-2xl">
        <section class="w-full max-w-xl space-y-12">
            <!-- Form Section -->
            <form class="space-y-6" @submit.prevent="submit">
                <div class="grid gap-2">
                    <Label for="name">Name</Label>
                    <Input
                        id="name"
                        v-model="form.name"
                        class="mt-1 block w-full"
                        placeholder="Role name"
                    />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div class="grid gap-2">
                    <Accordion
                        type="single"
                        class="w-full rounded-lg border px-4 py-2"
                        collapsible
                    >
                        <AccordionItem value="null" class="border-none">
                            <AccordionTrigger
                                class="cursor-pointer hover:no-underline"
                            >
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
                                        <p
                                            class="text-sm text-muted-foreground"
                                        >
                                            {{ module.description }}
                                        </p>
                                    </div>

                                    <div>
                                        <InputMultipleSelect
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
                </div>

                <div class="flex items-center gap-4">
                    <Button :disabled="form.processing" class="cursor-pointer">
                        Save
                    </Button>
                </div>
            </form>
        </section>
    </div>
</template>
