<template>
    <div>
        <div v-for="(reply, index) in items" :key="reply.id">
            <reply :data="reply" @deleted="remove(index)"></reply>
        </div>

        <paginator :dataSet="dataSet" @changed="fetch"></paginator>

        <new-reply @created="add"></new-reply>
    </div>
</template>

<script>
    import collection from "../mixins/collection";
    import Reply from './Reply';
    import NewReply from "./NewReply";

    export default {
        name: "Replies",
        components: {
            NewReply,
            Reply,
        },

        mixins: [collection],

        data() {
            return {
                dataSet: false,
            }
        },

        created() {
            this.fetch();
        },

        methods: {
            fetch(page) {
                if (!page) {
                    let query = location.search.match(/page=(\d+)/);

                    page = query ? query[1] : 1;
                }
                axios.get(`${location.pathname}/replies?page=${page}`)
                    .then(this.refresh);
            },

            refresh({data}) {
                this.dataSet = data;
                this.items = data.data;
            },
        },
    }
</script>

<style scoped>

</style>
