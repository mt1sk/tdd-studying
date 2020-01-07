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

            destroy() {
                axios.delete(`/replies/${this.reply.id}`);

                $(this.$el).fadeOut(300, () => {
                    flash('Your reply has been deleted.');
                });
            },
        },
    }
</script>

<style scoped>

</style>
