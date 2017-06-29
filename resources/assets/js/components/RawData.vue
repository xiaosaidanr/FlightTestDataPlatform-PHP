<template>
	<div id="menu_wrap">
        <div class="menu">
            <ul class="nav nav-tabs">
                <li v-for="frame, index in option['children']" :class="(index==0)?'active':''">
                    <a :href="'#'+frame['id_index']" data-toggle="tab">{{ frame['name'] }}</a>
                </li>
            </ul>
        </div>
        <label><input type = "checkbox" v-model="draggable">解锁页面</label>
        <button class="btn btn-primary" @click="saveConfig" style="float: right">保存页面配置</button>
        <div class="tab-content">
            <div v-for="frame, index in option['children']" :class="'tab-pane '+((index==0)?'active':'')" :id="frame['id_index']">
                <page :groups="frame" :index="index" :draggable="draggable" @innerPageChanged.capture="onInnerPageChanged" @layoutChanged.capture="onLayoutChanged"></page>
            </div>
        </div>
    </div>
</template>
<script>
    import page from '../components/page.vue'
    var sssss = require('jquery');
    sssss(window).scroll(function(){
        var menu_top = $('#menu_wrap').offset().top;
        if (sssss(window).scrollTop()>=menu_top) {
            sssss('.menu').addClass('menuFixed');
        }
        else{
            sssss('.menu').removeClass('menuFixed');
        }
    })
	export default{
		data: function(){
			return {
				option: {},
                draggable: false,
			}
		},
        components: {
            page,
        },
		methods: {
			onInnerPageChanged(val) {
                this.option['children'][val['index']] = val['value'];
            },
            onLayoutChanged(val) {
                this.option['children'][val['index']]['layout'] = val['layout'];
            },
            saveConfig() {
                var self = this;
                this.$http.post('/option', this.option).then(function (res) {
                    alert('post complete');
                });
            }
		},
		created: function () {
            var self = this;
            $.when(this.$http.get('/option')).then(function (res) {
                self.option = res.body.option;
            });
        }
	}
</script>