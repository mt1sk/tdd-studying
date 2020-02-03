<template>
    <div>
        <div v-if="signedIn">
            <div class="form-group">
                <textarea v-model="body" name="body" id="body" cols="30" rows="5" class="form-control" required placeholder="Body"></textarea>
                <button type="submit" class="btn btn-primary" @click="addReply">Post</button>
            </div>
        </div>
        <p class="text-center" v-else>
            Please <a href="/login">sign in</a> to participate.
        </p>
    </div>
</template>

<script>
    export default {
        name: "NewReply",

        props: [
            'endpoint',
        ],

        data() {
            return {
                body: '',
            }
        },

        computed: {
            signedIn() {
                return window.App.signedIn;
            },
        },

        methods: {
            addReply() {
                axios.post(this.endpoint, {body: this.body})
                    .then(({data}) => {
                        this.body = '';

                        flash('Your reply has been posted.');

                        this.$emit('created', data);
                    })
            },
        },
    }
</script>

<style scoped>

</style>
