<template>
    <div class="sortable-tree-ajax" :draggable="draggable && parentData" @dragstart.stop="dragStart($event)" @dragover.stop.prevent @dragenter.stop.prevent="dragEnter()"
         @dragleave.stop="removeDroperClass()" @drop.stop.prevent="drop" @dragend.stop.prevent="dragEnd">

        <div class="content">
            <a v-if="hasChildrenPotential" href="#" class="sign" @click.prevent="toggleNode()" @dragenter.stop.prevent="addDroperClass()" @dragleave.stop="removeDroperClass()">[<strong>{{ openSign }}</strong>]</a><router-link :to="linkIs" @click.prevent="editPage(linkIs)" @dragenter.stop.prevent="addDroperClass()" @dragleave.stop="removeDroperClass()" class="editing" :class="{ bold: isActive }"><span @dragenter.stop.prevent="addDroperClass()" @dragleave.stop="removeDroperClass()">{{ cname }}</span></router-link><a @click.prevent="removePage" :title="$t('admin-panel.delete')" href="#" class="removing" @dragenter.stop.prevent="addDroperClass()" @dragleave.stop="removeDroperClass()"><sup>[x]</sup></a>
        </div>

        <transition
                @enter="trEnter"
                @leave="trLeave"
                :css="false"
        >
            <ul v-if="open">
                <li v-for="(item, index) in preparedChildren" :class="{'parent-li': hasChildrenPotential, 'exist-li': !item['_replaceLi_'], 'blank-li': item['_replaceLi_']}" :key="item.id">
                    <sortable-tree-ajax :data="item" :attr="attr" :id="id" :childrenAttr="childrenAttr" :mixinParentKey="mixinParentKey" :closeStateKey="closeStateKey" :draggable="draggable"
                                   :parentData="data" :idx="index" :dragInfo="dragInfo">
                    </sortable-tree-ajax>
                </li>
            </ul>
        </transition>

    </div>
</template>
<!--this.$store.state.currentPage.name-->
<script>
    import Vue from 'vue'
    import axios from 'axios'
    import { mapState } from 'vuex';
    export default {
        name: 'SortableTreeAjax',
        props: {
            data: {
                type: Object
            },
            attr: {
                type: String,
                default: 'name'
            },
            id: {
                type: String,
                default: 'root'
            },
            closeStateKey: {
                type: String,
                default: ''
            },
            childrenAttr: {
                type: String,
                default: 'children'
            },
            mixinParentKey: {
                type: String,
                default: ''
            },
            draggable: {
                type: Boolean,
                default: true
            },
            // inner used from here
            parentData: {
                type: Object
            },
            idx: {
                type: Number, // v-for Индекс для идентификации соседних узлов
                default: -1
            },
            dragInfo: {
                type: Object,
                default: () => {
                    return {
                        data: null, // vm данные
                        vm: null, // Перетаскиваемый компонент
                        vmIdx: -1,
                        parentData: null, // vm Данные контейнера
                        $data: null
                    }
                }
            }
        },

        data () {
            return {
                dragObj: this.dragInfo,
                open: false,
                hoverCounter: 0,
                hasChildrenPotential: false,
            }
        },

        computed: {
            ...mapState({
                updatedBranchId: 'lastUpdatedBranchIfCreated'
            }),

            hasChildrenReal () {
                // if (!hasChildrenPotential) return false
                let children = this.data[this.childrenAttr]
                return (children && children.length > 0)
            },

            preparedChildren () {
                // Например: первоначально это было [N1, N2, N3]
                let children = this.data[this.childrenAttr]
                if (!children || !children.length) return []

                let _children = []
                children.forEach(child => _children.push({_replaceLi_: true}, child))
                _children.push({_replaceLi_: true})

                // Наконец, сгенерируйте [E1, N1, E2, N2, E3, N3, E4] (где N представляет узел, а E представляет пустой узел)
                return _children
            },

            openSign () {
                // console.info('openS')
                // console.log(this.hasChildrenPotential)
                // if (this.data.id == 'root') this.open = 1
                // if (!this.data.children) this.open = 0
                return (this.open) ? '-' : '+'
            },

            isActive () {
                return this.data.id == this.$store.state.currentPage.id
            },

            cname () {
                if (this.data.name == undefined) return undefined
                if (!this.isActive) return this.data.name
                else return this.$store.state.currentPage.name
            },

            linkIs () {
                return '/page/' + this.data.id
            },

            isParent () { // Пределы перетаскивания 1: Определите, является ли «I» родительским элементом перетаскиваемого узла
                return this.data === this.dragObj.parentData
            },

            isNextToMe () { // Ограничение перетаскивания 2: определить, является ли «I» соседним узлом перетаскиваемого узла
                return this.parentData === this.dragObj.parentData && Math.abs(this.idx - this.dragObj.vmIdx) === 1
            },

            isMeOrMyAncestor () { // Определите, является ли перетаскиваемый узел предком "I" или "I"
                let parent = this
                while (parent) {
                    let data = parent.data
                    if (data === this.dragObj.data) {
                        return true
                    }
                    parent = parent.$parent
                }
                return false
            },

            isAllowToDrop () { // Сочетание вышеуказанных ограничений перетаскивания
                return !(this.isNextToMe || this.isParent || this.isMeOrMyAncestor)
            }
        },

        methods: {
            trEnter (el, done) {
                $(el).hide().slideDown(100, () => {
                    done()
                })
            },

            trLeave (el, done) {
                $(el).slideUp(100, () => {
                    done()
                })
            },

            removePage () {
                // let test = _.get(tree, 'children.1.children.3.children.0')
                // _.unset(tree, 'children.1')

                let removeData = {
                    id: this.data.id
                }

                this.$confirm({
                    title: this.$t('admin-panel.confirm_delete'),
                    message: this.$t('admin-panel.really_delete'),
                    action: 'remove',
                    data: removeData
                }).then(() => {

                    let index = this.parentData[this.childrenAttr].indexOf(this.data)
                    this.parentData[this.childrenAttr].splice(index, 1)
                    if (!this.parentData[this.childrenAttr].length) {
                        this.$parent.$data.hasChildrenPotential = false
                    }

                }, () => {
                    console.error('cancel removing ' + this.data.id)
                })
            },

            async loadChildren () {
                try{
                    let vm = this
                    let response = await axios.post('/admin-panel/get-branch', {id: vm.data.id})
                    vm.$set(vm.data, 'children', response.data.data.children)
                    vm.open = 1
                    return response
                }catch(error){
                    return error
                }
            },

            toggleNode () {
                let opened = this.$session.get('sodplus-ru-opened-sortable-tree-nodes')
                if (!opened) {
                    opened = []
                }
                let id = this.data.id
                if (!this.open && this.hasChildrenPotential && !this.data.children) {
                    this.loadChildren().then(_ => {
                        if (opened && opened.indexOf(id) === -1) {
                            opened.push(id)
                            this.$session.set('sodplus-ru-opened-sortable-tree-nodes', opened)
                        }
                    }, (error) => {
                        console.error(error)
                    })
                }
                else {
                    if (this.open) {
                        this.open = false
                        _.pull(opened, id)
                    }
                    else {
                        this.open = true
                        opened.push(id)
                    }
                }
                this.$session.set('sodplus-ru-opened-sortable-tree-nodes', opened)
            },

            dragStart (event) { // Перетаскиваемый элемент
                if (this.data['_replaceLi_']) { // Пустые элементы не позволяют перетаскивать
                    return event.preventDefault()
                }
                // support firefox ..
                event.dataTransfer.setData('text/plain', null)
                this.dragObj.data = this.data
                this.dragObj.vm = this.$el
                this.dragObj.vmIdx = this.idx
                this.dragObj.parentData = this.parentData
                this.dragObj.pastIdx = (this.idx - 1) / 2
                this.dragObj.$this= this
                this.dragObj.parentData.$this = this.$parent
            },

            addDroperClass () {
                this.hoverCounter++
                this.$el && this.$el.classList.add('droper')
            },

            removeDroperClass () {
                this.hoverCounter--
                if (this.hoverCounter <= 0) {
                    this.$el && this.$el.classList.remove('droper')
                }
            },

            dragEnter () { // Роль в целевом элементе
                this.dragObj.vm && this.dragObj.vm.classList.add('draging')
                if (!this.isAllowToDrop) return
                this.addDroperClass()
            },

            // Функция preventDefault () должна выполняться в ondragover, иначе событие ondrop не будет запущено.
            drop () { // Целевой элемент
                let afterParent = this.parentData
                if (!this.data['_replaceLi_']) {
                    afterParent = this.data
                }
                let moveData = {
                    data: this.dragObj.data,
                    afterParent: afterParent,
                    newIndex: this.idx / 2,
                }

                this.$confirm({
                    title: this.$t('admin-panel.confirm_move'),
                    message: this.$t('admin-panel.really_move'),
                    action: 'move',
                    data: moveData
                }).then(() => {

                    this.dragObj.vm && this.dragObj.vm.classList.remove('draging')
                    this.removeDroperClass()

                    if (!this.isAllowToDrop) return
                    // Удалять перетаскиваемый узел в любом случае
                    let index = this.dragObj.parentData[this.childrenAttr].indexOf(this.dragObj.data)
                    this.dragObj.parentData[this.childrenAttr].splice(index, 1)
                    // Перетащите в пустой узел и станьте его братом (используйте сращивание для вставки узла)
                    if (this.data['_replaceLi_']) {
                        if (this.dragObj.parentData === this.parentData) {
                            let changedIdx = this.idx / 2
                            if (index > changedIdx) {
                                this.parentData[this.childrenAttr].splice(changedIdx, 0, this.dragObj.data)
                            } else {
                                this.parentData[this.childrenAttr].splice(changedIdx - 1, 0, this.dragObj.data)
                            }
                        } else {
                            this.parentData[this.childrenAttr].splice(this.idx / 2, 0, this.dragObj.data)
                        }

                    }
                    else {
                        if (this.hasChildrenPotential && !this.hasChildrenReal) {
                            this.loadChildren()
                        }
                        else {
                            // Перетаскиваем в обычный узел, чтобы стать его дочерним
                            if (!this.data[this.childrenAttr]) {
                                Vue.set(this.data, [this.childrenAttr], [])
                            } // Необходимо использовать $ set, чтобы ввести двустороннюю привязку
                            this.data[this.childrenAttr].push(this.dragObj.data)
                        }
                    }
                    this.dragObj.parentData.$this.hasChildrenPotential = !!(this.dragObj.parentData[this.childrenAttr].length)
                    this.hasChildrenPotential = true
                    this.open = true

                }, (error) => {
                    console.error(error)
                    this.dragObj.vm && this.dragObj.vm.classList.remove('draging')
                    this.removeDroperClass()
                })

            },

            dragEnd () {
            }
        },

        watch: {
            updatedBranchId (newValue) {
                if (newValue == this.data.id) {
                    this.hasChildrenPotential = true
                    this.loadChildren()
                }
            }
        },

        updated () {
            if (this.mixinParentKey) {
                this.data[this.mixinParentKey] = this.parentData
            }
        },

        mounted () {
            if (this.data.initOpen) this.open = true
            if (this.mixinParentKey) {
                this.data[this.mixinParentKey] = this.parentData
            }
        },

        created () {
            this.open = !(this.data.id)
            // console.log(this.data.has_children)
            this.hasChildrenPotential = this.data.has_children || this.open
        }
    }
</script>

<style lang="scss" scoped>
    $content-height: 30px;
    $blank-li-height: 5px;
    $border-color: #007bff;

    .sortable-tree-ajax {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 16px;
        min-height: $blank-li-height;
        left: -14px;
        position: relative;
        .content {
            height: $content-height;
            line-height: $content-height;
            user-select:none;
            position: relative;
            z-index: 999;
            .sign {
                background: #e1ffff;
                &:hover {
                    text-decoration: none;
                }
            }
            .removing {
                /*background: #ffe0db;*/
                color: #ff0800;
            }
            .editing {
                background: #dfffdb;
                color: #053800;
            }
        }

        .blank-li {
            .content {
                width: 0;
                height: 0;
                overflow: hidden;
            }
            .sortable-tree-ajax {
                left: 2px;
            }
        }

        ul, li {
            margin: 0;
            padding: 0;
        }

        ul {
            position: relative;
            display: list-item;
            list-style: none;
            padding-left: 14px;
            &:empty {
                width: 0;
                height: 0;
            }
        }

        li {
            position: relative;
            padding-left: 24px;
        }
    }
    /* Месторасположение */
    .sortable-tree-ajax {
        li {
            position: relative;

            &:before, &:after {
                position: absolute;
                content: '';
            }
            &:before {
                width: 24px;
                height: 100%;
                left: 0;
                top: $content-height / -2;
                /*background: red;*/
                border-left: 1px solid $border-color;
            }
            &:after {
                width: 9px;
                height: $content-height;
                top: $content-height / 2;
                left: 0;
                border-top: 1px solid $border-color;
            }

            &.parent-li:nth-last-child(2):before {
                width: 24px;
                height: $content-height; // 32 - высота 1 лита
                left: 0;
                top: $content-height / -2;
                border-left: 1px solid $border-color;
            }
            &.blank-li{
                margin: 0;
                padding: 0;
                width: 100%;
                height: $blank-li-height;

                &:after {
                    width: 0;
                }

                &:last-child {
                    height: 0;
                }
            }
            a.bold {
                &:hover {
                    text-decoration: none;
                }
                span{
                    color: #3f003c;
                    background: #ffdbf2;
                    border-bottom: 2px dashed #3f003c;
                }
            }
        }
    }
    // Перетаскивание
    .draging {
        background: #EFEEEF;
    }
    .droper {
        background: lightgreen;
    }
</style>
