<template>
    <div>
        <button :class="classes" @click="subscribe">Subscribe</button>
    </div>
</template>

<script>
    export default {
        name: "SubscribeButton",

        props: [
            'active',
        ],

        data() {
            return {
                isActive: this.active,
            };
        },

        computed: {
            classes() {
                return ['btn', this.isActive ? 'btn-danger' : 'btn-primary'];
            },
        },

        methods: {
            subscribe() {
                axios[(this.isActive ? 'delete' : 'post')](location.pathname + '/subscriptions');

                this.isActive = ! this.isActive;

                flash('Subscribed.');
            },
        },
    }
</script>

<style scoped>

</style>
