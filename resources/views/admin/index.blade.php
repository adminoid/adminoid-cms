@extends('admin/admin')

@section('title', 'Admin panel')

@section('content')

    <div class="row">
        <loading :active.sync="isLoading"></loading>

        <transition
                @enter="sbEnter"
                @leave="sbLeave"
                :css="false"
        >
            <div class="col-lg-5 col-md-7" id="sidebar" v-show="sidebar">

                <sortable-tree-ajax :data="treeData" attr="name" id="id">
                    <template slot-scope="{item}">
                        <span>@{{item.name}}</span>
                    </template>
                </sortable-tree-ajax>

            </div>
        </transition>

        <div :class="sbClasses">
            <router-view></router-view>
        </div>
    </div>

@endsection
