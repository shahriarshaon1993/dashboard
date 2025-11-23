<script setup lang="ts">
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetDescription, SheetFooter, SheetClose } from "@/components/ui/sheet";
import { Button } from "@/components/ui/button";

interface Props {
    modelValue: boolean;
    title?: string;
    description?: string;
    submitLabel?: string;
    isFooterShown?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    isFooterShown: false,
});

const emit = defineEmits(["update:modelValue", "submit"]);

const closeSheet = () => emit("update:modelValue", false);
</script>

<template>
    <Sheet :open="props.modelValue" @update:open="emit('update:modelValue', $event)">
        <!-- Sheet body -->
        <SheetContent class="w-full sm:w-[60vw] sm:max-w-5xl">
            <div class="flex flex-col h-full max-h-screen">
                <SheetHeader class="border-b border-b-gray-300">
                    <SheetTitle>{{ props.title }}</SheetTitle>
                    <SheetDescription>{{ props.description }}</SheetDescription>
                </SheetHeader>

                <!-- Custom form fields -->
                <div class="flex-1 overflow-y-auto overflow-x-hidden">
                    <slot />
                </div>

                <!-- Footer -->
                <SheetFooter v-if="isFooterShown">
                    <Button type="button" @click="emit('submit')">
                        {{ props.submitLabel || 'Save changes' }}
                    </Button>

                    <SheetClose as-child>
                        <Button variant="outline" @click="closeSheet">
                            Close
                        </Button>
                    </SheetClose>
                </SheetFooter>
            </div>
        </SheetContent>
    </Sheet>
</template>
