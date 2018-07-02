<template>
    <form>

        <div v-if="available">
            <h1>{{ $t('admin-panel.page_edit') }}</h1>

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

            <div v-if="form.locked_content">
                <h4>{{ $t('admin-panel.edit_template') }}: {{ form.template }}</h4>
            </div>
            <div v-else class="form-group" v-bind:class="{ 'is-invalid': errors.content }">
                <label for="form.content">{{ $t('admin-panel.content') }}</label>

                <Summernote id="form.content" height="350" :model.sync="form.content" :itemId="form.id" v-bind:class="{ 'is-invalid': errors.content }"></Summernote>
                <div v-if="errors.content" class="invalid-feedback">
                    {{ errors.content[0] }}
                </div>
            </div>

            <button type="button" class="btn btn-primary btn-lg" @click.prevent="savePage">{{ $t('admin-panel.save') }}</button>

            <button type="button" class="btn btn-dark" @click="createPage">{{ $t('admin-panel.create_children') }}</button>

        </div>

        <div v-else>
            <h1>{{ $t('admin-panel.edit_unavailable') }}</h1>
        </div>

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
        beforeRouteEnter (to, from, next) {
            next(vm => {
                axios.post('/admin-panel/page', {
                id: to.params.id
            })
                .then(function (response) {
                    vm.available = 1
                    vm.form = response.data.page
                    vm.form.id = to.params.id
                })
                .catch(function (error) {
                    vm.available = 0
                    console.error(error)
                })
            })
        },
        beforeRouteUpdate (to, from, next) {
            // console.log('From id: ' + from.params.id)
            // console.log('To id: ' + to.params.id)
            const vm = this
            axios.post('/admin-panel/page', {
                id: to.params.id
            })
                .then(function (response) {
                    vm.available = 1
                    vm.form = response.data.page
                    vm.form.id = to.params.id
                })
                .catch(function (error) {
                    vm.available = 0
                    console.error('Petja ' + error)
                })

            next()
        },
        data () {
            return {
                form:  {
                    name: '',
                    title: '',
                    content: '',
                    slug: '',
                    id: -1,
                    active: 0
                },
                errors: {},
                available: 0
            }
        },
        methods: {
            createPage () {
                this.$router.push({ name: 'createpage', params: { parentId: this.form.id }})
            },
            savePage () {
                let data = {}
                data = this.form
                // data.parent_id = this.$route.params.id

                let vm = this
                axios.post('/admin-panel/page/save', data)
                    // .then(function (response) {
                    //     console.info('do update tree (EDIT)')
                    // })
                    .catch(function (error) {
                        if (error.response.data.errors) vm.errors = error.response.data.errors
                        else vm.errors = {}
                    })
            }
        },
        watch: {
            'form.name': function () {
                // console.log('form.name changed')
                // console.info(this.form)
                this.$store.commit('setCurrentPage', this.form)
            }
        }
    }
</script>
