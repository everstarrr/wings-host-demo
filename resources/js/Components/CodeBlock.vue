<script setup>
import { ref } from "vue";

const props = defineProps({
    format: null,
    code: null,
});

const formated_code = ref();

const parse_json = (json) => {
    json = JSON.parse(json);
    if (typeof json != "string") {
        json = JSON.stringify(json, undefined, 2);
    }

    json = json.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
    return json.replace(
        /("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g,
        function (match) {
            // number
            let cls = "text-amber-500";
            if (/^"/.test(match)) {
                if (/:$/.test(match)) {
                    //key
                    cls = "text-red-500";
                } else {
                    // string
                    cls = "text-green-500";
                }
            } else if (/true|false/.test(match)) {
                // boolean
                cls = "text-violet-500";
            } else if (/null/.test(match)) {
                // null
                cls = "text-purple-500";
            }
            return '<span class="' + cls + '">' + match + "</span>";
        },
    );
};

if (props.format === "tag:json") {
    formated_code.value = parse_json(props.code.split(":").slice(1).join(":"));
} else if (props.format === "json") {
    formated_code.value = parse_json(props.code);
}
</script>

<template>
    <div
        class="p-2 text-neutral-400 text-base bg-neutral-800 rounded-md whitespace-pre font-['JetBrains-Mono'] overflow-x-scroll hide-scrollbar">
        <slot v-if="!code" />
        <span v-html="formated_code" />
    </div>
</template>
