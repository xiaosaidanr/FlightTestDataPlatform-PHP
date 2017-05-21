<template>
	<div class="">
        <ul class="nav nav-tabs">
            <li v-for="frame, index in option['children']" :class="(index==0)?'active':''">
                <a :href="'#'+frame['id_index']" data-toggle="tab">{{ frame['name'] }}</a>
            </li>
        </ul>
        <div class="tab-content">
            <div v-for="frame, index in option['children']" :class="'tab-pane '+((index==0)?'active':'')" :id="frame['id_index']">
                <page :groups="frame" :index="index" @innerPageChanged.capture="onInnerPageChanged" @layoutChanged.capture="onLayoutChanged"></page>
<!--                 <table class="table table-bordered table-striped table-hover">
                    <tbody>
                      <tr class="fatal">
                        <th>test</th>
                      </tr>
                      <tr v-for="param in frame['children']" class="">
                        <td>
                            <template v-if="param['id_index']">
                                <item :name="param['name']" :value="0" :editing="false"/>
                            </template>
                            <template v-else>
                                <group :name="param['name']" :items="param['children']" @innerItemsChanged.capture="onInnerItemsChanged"/>
                            </template>
                        </td>
                      </tr>
                    </tbody>
                </table> -->
            </div>
        </div>
        <button @click="saveConfig">save_config</button>
    </div>
</template>
<script>
    // import item from '../components/item.vue'
    // import group from '../components/group.vue'
    import page from '../components/page.vue'
	export default{
		data: function(){
			return {
				option: {},
			}
		},
        components: {
            // item,
            // group,
            page,
        },
		methods: {
			onInnerPageChanged(val) {
                // alert('event from page');
                this.option['children'][val['index']] = val['value'];
                // console.log(this.option);
            },
            onLayoutChanged(val) {
                // alert('layout changed');
                this.option['children'][val['index']]['layout'] = val['layout'];
                // console.log(this.option);
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
                // console.log(self.option);
            });
        }
	}
</script>