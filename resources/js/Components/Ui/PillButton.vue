<script setup>
import { computed } from "vue";
import { cn } from "../../lib/utils";

const props = defineProps({
    class: {
        type: String,
        default: ""
    },
    variant: {
        type: String,
        default: "primary",
        validator(value) {
            return ["primary", "outlined"].includes(value);
        }
    },
    as: {
        type: [String, Object],
        default: "button"
    }
});

const variantClasses = {
    primary: "bg-primary text-white",
    outlined: "ring ring-inset ring-primary text-primary"
};

const mergedClasses = computed(() => {
    return cn("flex items-center gap-3 px-6 py-2 rounded-full font-medium", variantClasses[props.variant], props.class);
});
</script>

<template>
    <component :is="as" :class="mergedClasses">
        <slot />
    </component>
</template>
