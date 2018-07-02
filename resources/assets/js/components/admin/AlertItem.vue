<template>
    <div :class="data.type" class="alert alert-dismissible fade show">
        {{ data.msg }}
        <button type="button" class="close" @click="closeAlert">
            <span>&times;</span>
        </button>
    </div>
</template>

<script>
    export default {
        name: "AlertItem",
        props: {
            data: Object
        },
        mounted () {
            // console.log(this.$el.dataset.index)
            setTimeout(() => {
                this.$emit('close', this.$el.dataset.index)
            }, 3000)
        },
        methods: {
            closeAlert () {
                this.$emit('close', this.$el.dataset.index)
            }
        }
    }
</script>
