<template>

    <div class="modal-mask" v-if="state.active">
        <div class="modal-wrapper">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ state.title }}</h5>
                        <button type="button" @click="cancel" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>{{ state.message }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="confirm">{{ $t('admin-panel.confirm') }}</button>
                        <button type="button" @click="cancel" class="btn btn-secondary" data-dismiss="modal">{{ $t('admin-panel.cancel') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ModalWindow",
        props: {
            state: {
                type: Object
            }
        },
        methods: {
            confirm () {
                this.$emit('confirmed')
            },
            cancel () {
                this.$emit('canceled')
            }
        },
        mounted () {
            console.log('Modal Window mounted')
        }
    }
</script>

<style scoped>
    .modal-mask {
        position: fixed;
        z-index: 9998;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, .5);
        display: table;
        transition: opacity .3s ease;
    }
    .modal-wrapper {
        display: table-cell;
        vertical-align: middle;
    }
</style>
