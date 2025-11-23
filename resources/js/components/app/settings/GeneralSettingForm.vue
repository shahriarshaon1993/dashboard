<script setup lang="ts">
import FileUploader from '@/components/app/FileUploader.vue';
import { GeneralSetting, GeneralSettingForm } from '@/types/generalSetting';
import InputError from '@/components/InputError.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import { toast } from 'vue-sonner';
import { useForm, usePage } from '@inertiajs/vue3';
import { edit } from '@/routes/general-settings';
import { SharedData } from '@/types';

const props = defineProps<{
    setting: GeneralSetting;
}>();

const page = usePage<SharedData>();

const form = useForm<GeneralSettingForm>({
    site_logo: null,
    site_title: props.setting.site_title ?? '',
    date_format: props.setting.date_format ?? '',
    developed_by: props.setting.developed_by ?? '',
    logo_removed: false
});

const submit = () => {
    form.post(edit().url, {
        preserveScroll: true,
        forceFormData: true,
        headers: {
            'X-HTTP-Method-Override': 'PATCH',
        },
        onSuccess: () => {
            toast('Success!', { description: page.props.flash.success });
        },
    })
};
</script>

<template>
    <div class="w-full md:max-w-2xl">
        <section class="w-full max-w-xl space-y-12">
            <!-- Form Section -->
            <form class="space-y-6" @submit.prevent="submit">
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                    <div class="col-span-full">
                        <Label for="site_title">System title</Label>
                        <div class="mt-2">
                            <Input
                                id="site_title"
                                v-model="form.site_title"
                                class="mt-1 block w-full"
                                placeholder="System title"
                            />
                        </div>
                        <InputError :message="form.errors.site_title" />
                    </div>

                    <div class="col-span-full">
                        <Label for="date_format">Date format</Label>
                        <div class="mt-2">
                            <Input
                                id="date_format"
                                v-model="form.date_format"
                                class="mt-1 block w-full"
                                placeholder="System title"
                            />
                        </div>
                        <InputError :message="form.errors.date_format" />
                    </div>

                    <div class="col-span-full">
                        <Label for="developed_by">Developed by</Label>
                        <div class="mt-2">
                            <Input
                                id="developed_by"
                                v-model="form.developed_by"
                                class="mt-1 block w-full"
                                placeholder="System title"
                            />
                        </div>
                        <InputError :message="form.errors.developed_by" />
                    </div>

                    <div class="col-span-full">
                        <Label for="developed_by">Site logo</Label>
                        <FileUploader
                            :key="setting?.id"
                            :multiple="false"
                            :maxSizeMB="2"
                            accept="image/*"
                            :initial-urls="setting?.site_logo ? [setting?.site_logo] : []"
                            @update:files="(f: any) => (form.site_logo = f[0] ?? null)"
                            @initial-removed="form.logo_removed = true"
                            class="mt-2"
                        />
                        <InputError :message="form.errors.site_logo" />
                    </div>
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
