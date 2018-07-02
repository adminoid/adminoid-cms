<template>
    <textarea class="form-control" rows="10"></textarea>
</template>

<script>
    import axios from 'axios'
    import Tooltip from 'bootstrap'
    export default {
        props : {
            model: {
                required: true
            },
            height: {
                type: String,
                default: '350'

            },
            itemId: {}
        },
        watch: {
            itemId: function () {
                // console.log('model changed')
                this.reloadContent()
            }
        },
        data () {
            return {
                config: {}
            }
        },
        mounted() {
            require('summernote/dist/summernote-bs4')
            let config = {
                height: this.height,
                codemirror: {
                    mode: 'text/html',
                    htmlMode: true,
                    lineNumbers: true,
                    lineWrapping: true
                },
            }
            let vm = this
            config.callbacks = {
                onInit: function () {
                    $(vm.$el).summernote("code", vm.model)
                    // $(vm.$el).summernote('codeview.activate')
                },
                onChange: function () {
                    vm.$emit('update:model', $(vm.$el).summernote('code'))
                },
                onImageUpload: function(files, editor, welEditable) {
                    // console.log(vm.$route.params.id)
                    vm.sendFile(files[0], editor, welEditable);
                }
            }
            this.config = config
            this.reloadContent()
        },
        methods: {
            sendFile (file, editor, welEditable) {
                let data = new FormData();

                data.append("file", file);
                data.append("page_id", this.$route.params.id);
                // let editor_ = editor
                let vm = this
                axios.post('/admin-panel/image-upload', data)
                    .then(function (response) {
                        console.log(response.data)
                        $(vm.$el).summernote('editor.insertImage', response.data)
                    })
                    .catch(function (error) {
                        console.error(error)
                    })
            },
            reloadContent () {
                $(this.$el).summernote(this.config)
                $(this.$el).summernote("code", this.model);
                // console.info('reloading')
                if ($(this.$el).summernote('codeview.isActivated')) {
                    let editor = $('.CodeMirror')[0].CodeMirror
                    editor.setValue(this.model)
                    let vm = this
                    editor.on('change', function (cm) {
                        // console.info('change')
                        // console.log(cm.getValue())
                        vm.$emit('update:model', cm.getValue())
                    })
                }
            }
        }
    }
</script>

<style lang="css">
    @import '~summernote/dist/summernote-bs4.css';
    @import '~codemirror/lib/codemirror.css';
    .CodeMirror {
        font-family: "Open Sans", sans-serif;
        font-size: 14px;
    }
</style>