<template>
    <div>
        <div class="content">
            <b-card :title="title">
                <div v-html="html"></div>
            </b-card>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                Loading: true,
                title: '',
                html: '',
            }
        },
        props: {
            id: {
                type: Number,
                default: 0
            },
        },
        mounted() {
            this.getArticle();
        },
        methods: {
            getArticle()
            {
                this.$root.$emit('update:isLoading', true);

                //if( ! this.id )
                //    return null;

                axios.get('/api/article/'+this.id)
                    .then((res) => {

                        this.title = res.data.name;
                        this.html = res.data.html;

                        // выключаем лоадер
                        this.$root.$emit('update:isLoading', false);
                    })
                    .catch((err) => {
                        console.log(err);
                    });
            }
        },
        watch: {
            '$route.params.id'(newId, oldId) {
                this.getArticle(newId)
            }
        },
        components: {
            //'Pagination': require('laravel-vue-pagination'),
        }
    }
</script>
<style>
</style>
