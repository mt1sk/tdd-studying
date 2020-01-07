<template>

</template>

<script>
    export default {
        name: "Reply",
        props: ['reply'],
        data() {
            return {
                editing: false,
                body: this.reply.body,
            }
        },

        methods: {
            update() {
                axios.patch(`/replies/${this.reply.id}`, {
                    body: this.body,
                });

                this.editing = false;

                flash('Updated!');
            },
        },
    }
</script>

<style scoped>

</style>
