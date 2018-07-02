<template>
    <form>
        <h1>{{ title }}</h1>

        <div class="form-group">
            <label for="form.name">{{ $t('admin-panel.menu_item') }}</label>
            <input type="text" class="form-control" v-bind:class="{ 'is-invalid': errors.name }" id="form.name" :placeholder="$t('admin-panel.menu_item')" v-model="form.name">
            <div v-if="errors.name" class="invalid-feedback">
                {{ errors.name[0] }}
            </div>
        </div>

        <div class="form-group">
            <label for="form.title">{{ $t('admin-panel.header') }}</label>
            <input type="text" class="form-control" v-bind:class="{ 'is-invalid': errors.title }" id="form.title" :placeholder="$t('admin-panel.header')" v-model="form.title">
            <div v-if="errors.title" class="invalid-feedback">
                {{ errors.title[0] }}
            </div>
        </div>

        <div class="form-group">
            <label for="form.slug">{{ $t('admin-panel.address') }}</label>
            <input type="text" class="form-control" v-bind:class="{ 'is-invalid': errors.slug }" id="form.slug" :placeholder="$t('admin-panel.address')" v-model="form.slug">
            <div v-if="errors.slug" class="invalid-feedback">
                {{ errors.slug[0] }}
            </div>
        </div>

        <div class="form-group" v-bind:class="{ 'is-invalid': errors.content }">
            <label for="form.content">{{ $t('admin-panel.content') }}</label>
            <Summernote id="form.content" height="350" :model.sync="form.content" v-bind:class="{ 'is-invalid': errors.content }"></Summernote>
            <div v-if="errors.content" class="invalid-feedback">
                {{ errors.content[0] }}
            </div>
        </div>

        <button type="button" class="btn btn-primary btn-lg" @click.prevent="savePage">{{ $t('admin-panel.save') }}</button>

    </form>
</template>
<script>
    // TODO: npm i css-loader
    // TODO: remove fonts folder and scss file
    import axios from 'axios'
    import Summernote from './summernote/Summernote'
    export default {
        components: {
            Summernote
        },
        data () {
            return {
                title: this.$t('admin-panel.page_create'),
                form:  {
                    name: '',
                    title: '',
                    content: '',
                    slug: ''
                },
                errors: {}
            }
        },
        mounted () {
            if (!this.$route.params.id) this.title = this.title + ' (' + this.$t('admin-panel.root') + ')'
        },
        methods: {
            savePage () {
                let data = {}
                data = this.form
                data.parent_id = (this.$route.params.id) ? this.$route.params.id : 'root'
                let vm = this
                axios.post('/admin-panel/page/create', data)
                    .then(function (response) {
                        vm.$store.commit('updateBranch', data.parent_id)
                        let uri = '/page/' + response.data.id
                        vm.$router.push(uri)
                    })
                    .catch(function (error) {
                        if (error.response.data.errors) vm.errors = error.response.data.errors
                        else vm.errors = {}
                    })
            }
        }
    }
</script>
