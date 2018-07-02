
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./admin.bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue router stuff:
import VueRouter from 'vue-router'
Vue.use(VueRouter)
import EditPage from './components/admin/EditPage'
import CreatePage from './components/admin/CreatePage'
import Vuex from 'vuex'
Vue.use(Vuex)
import SortableTreeAjax from './components/admin/SortableTreeAjax'
import axios from 'axios'
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.min.css'
Vue.use(Loading)
import AlertItem from './components/admin/AlertItem'
import VueSession from 'vue-session'
Vue.use(VueSession, {
    persist: true
})
import VueInternationalization from 'vue-i18n'
import Locale from './vue-i18n-locales.generated';
Vue.use(VueInternationalization)
const lang = document.documentElement.lang.substr(0, 2);
const i18n = new VueInternationalization({
    locale: lang,
    messages: Locale
});

const router = new VueRouter({
    routes: [
        {
            path: '/page/root',
            component: CreatePage,
        },
        {
            path: '/page/:id',
            component: EditPage,
        },
        {
            name: 'createpage',
            path: '/page/:id/create',
            component: CreatePage,
        },
    ]
})

require('./admin.modals');

const store = new Vuex.Store({
    state: {
        currentPage: {
            name: '', id: ''
        },
        treeData: {},
        lastUpdatedBranchIfCreated: 0
    },
    mutations: {
        setCurrentPage (state, data) {
            state.currentPage = data
        },
        updateTree (state, data) {
            state.treeData = data
        },
        updateBranch (state, id) {
            state.lastUpdatedBranchIfCreated = id
        }
    },
    actions: {
        async synchronizeTree ({ commit }, opened) {
            try {
                let response = await axios.post('/admin-panel/tree', {
                    opened: opened
                })
                commit('updateTree', response.data.data)
                return response
            }
            catch (e) {
                return e
            }
        }
    }
})

new Vue({
    el: '#app',
    router,
    store,
    i18n,
    data() {
        return {
            isLoading: false,
            alerts: []
        }
    },
    components: {
        [SortableTreeAjax.name]: SortableTreeAjax,
        Loading, AlertItem
    },
    methods: {
        pushAlert (data) {
            let maxAlert = _.maxBy(this.alerts, function(alert) {
                return alert.id
            })
            let maxId = (maxAlert) ? maxAlert.id : 0
            data.id = maxId + 1
            this.alerts.push(data)
        },
        removeAlert (idx) {
            this.alerts.splice(idx, 1)
        }
    },
    mounted () {
        // this.$session.clear()
        // console.info(this.$session.getAll())
        axios.interceptors.request.use(config => {
            this.isLoading = true
            return config
        })
        axios.interceptors.response.use(response => {
            this.isLoading = false
            this.pushAlert({
                msg: response.data.msg,
                type: 'alert-success'
            })
            return response
        }, error => {
            this.isLoading = false
            this.pushAlert({
                msg: error.message,
                type: 'alert-danger'
            })
            return Promise.reject(error)
        })
        let opened = this.$session.get('sodplus-ru-opened-sortable-tree-nodes')
        // console.log(opened)
        this.$store.dispatch('synchronizeTree', opened)
    },
    computed: {
        treeData () {
            return this.$store.state.treeData
        }
    }
});
