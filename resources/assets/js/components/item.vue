<template>
	<!-- <div class="col-md-2 col-sm-2 data_item" style="padding: 0px;border:1px solid;"> -->
	<div class="col-md-6 col-sm-6 data_item" style="padding: 0px;">
		<div class="col-md-7 col-sm-6 data_item_name" style="padding: 0px;">
			<label style="word-wrap: break-word;">{{ name }}</label>
		</div>
		<div class="col-md-5 col-sm-6 data_item_value" style="padding: 0px;">
			<template v-if="editing">
				<button @click="delete_self">删除</button>
			</template>
			<template v-else>
				<label style="word-wrap: break-word;">{{ value_to_show }}</label>
			</template>
		</div>
	</div>
</template>
<script>
	import { mapState } from 'vuex'
	export default{
		props: ['name', 'idIndex', 'index', 'editing'],
		data: function(){
			return {
				
			}
		},
		methods: {
			delete_self() {
				this.$emit('itemDelete', this.index);
			},
		},
		computed: {
			value_to_show(){
				var tmp = '';
				// if (this.name == '拉力幅值1') {
				// 	console.log(Array.isArray(this.idIndex));
				// }
				if (Array.isArray(this.idIndex)) {
					for (let i = 0; i < this.idIndex.length; i++) {
						// console.log(this.realtimeData[this.idIndex[i]]);
						tmp = tmp + ' ' + this.realtimeData[this.idIndex[i]];
					}
					// console.log(tmp);
				}
				else{
					tmp = this.realtimeData[this.idIndex];
				}
				return tmp;
			},
			...mapState({
				realtimeData: state => state.realtimeData,
				realtimeDataAlias: 'realtimeData',
			})
		},
	}
</script>