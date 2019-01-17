<template>
    <div v-if="form.errors.has(field)" class="invalid-feedback" v-html="form.errors.get(field)"></div>
</template>

<script>
    export default {
        name: "FieldError",
        props: [
            'form',
            'field'
        ]
    }
</script>