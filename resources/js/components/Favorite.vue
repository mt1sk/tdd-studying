<template>
    <div>
        <button :class="classes" @click="toggle">
            <span v-text="count"></span>
        </button>
    </div>
</template>

<script>
    export default {
        name: "Favorite",
        props: ['reply'],

        data() {
            return {
                count: this.reply.favoritesCount,
                active: this.reply.isFavorited,
            }
        },

        methods: {
            toggle() {
                this.active ? this.destroy() : this.create();
            },

            create() {
                axios.post(this.endpoint);

                this.active = true;
                this.count++;
            },

            destroy() {
                axios.delete(this.endpoint);

                this.active = false;
                this.count--;
            }
        },

        computed: {
            classes() {
                return [
                    'btn',
                    this.active ? 'btn-primary' : 'btn-light',
                ];
            },

            endpoint() {
                return `/replies/${this.reply.id}/favorites`;
            },
        },
    }
</script>

<style scoped>

</style>
