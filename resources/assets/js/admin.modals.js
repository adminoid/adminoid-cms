import ModalWindow from './components/admin/ModalWindow'
import axios from "axios/index";

import VueInternationalization from 'vue-i18n'
import Locale from './vue-i18n-locales.generated';
Vue.use(VueInternationalization)
const lang = document.documentElement.lang.substr(0, 2);
const i18n = new VueInternationalization({
    locale: lang,
    messages: Locale
});


Vue.prototype.$confirm = function(windowData) {
    return new Promise(function (resolve, reject) {
        class ModalData {
            constructor() {
                this.clear()
            }

            clear() {
                this.title = ''
                this.message = ''
                this.active = false
            }

            open(newData) {
                if (!!newData.title && !!newData.message) {
                    this.title = newData.title
                    this.message = newData.message
                    this.action = newData.action
                    this.data = newData.data
                    this.active = true
                }
            }

            close() {
                this.active = false
                $('#modal-place').empty().append('<div id="modals"></div>')
            }
        }

        let Modal = new ModalData()

        Modal.open(windowData)

        new Vue({
            el: '#modals',
            i18n,
            template: '<modal-window :state="modal" @confirmed="confirmedAction" @canceled="canceledAction"></modal-window>',
            components: {
                ModalWindow
            },
            data () {
                return {
                    modal: Modal
                }
            },
            methods: {
                confirmedAction () {
                    this.modal.close()
                    this.initAction(this.modal.action)
                },
                canceledAction () {
                    this.modal.close()
                    reject(`Action is cancelled`)
                },
                initAction (action) {
                    // console.error('resy')
                    switch (action) {
                        case 'remove':
                            axios.post('/admin-panel/delete-page', {
                                id: this.modal.data.id
                            })
                                .then(() => {
                                    resolve()
                                })
                                .catch(error => {
                                    reject(`Network error: ${error.response}`)
                                })

                            break
                        case 'move':
                            let moveData = {
                                nodeId: this.modal.data.data.id,
                                toParentId: this.modal.data.afterParent.id,
                                newIndex: this.modal.data.newIndex
                            }
                            axios.post('/admin-panel/tree-move', moveData)
                                .then(() => {
                                    resolve()
                                })
                                .catch(error => {
                                    reject(`Network error: ${error.response}`)
                                })
                            break
                        default:
                            reject(`Action ${action} is wrong`)
                    }
                }
            }
        })
    })
}
