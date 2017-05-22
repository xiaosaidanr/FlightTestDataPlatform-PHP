<template>
	<div class="col-md-6 col-sm-6 data_item" style="padding: 0px;">
		<div class="col-md-7 col-sm-6 data_item_name" style="padding: 0px;">
			<label style="word-wrap: break-word;">{{ name }}</label>
		</div>
		<div class="col-md-5 col-sm-6 data_item_value" style="padding: 0px;">
			<template v-if="editing">
				<button @click="delete_self">删除</button>
			</template>
			<template v-else>
				<label :style="labelStyle">{{ value_to_show }}</label>
			</template>
		</div>
	</div>
</template>
<script>
	import { mapState } from 'vuex'
	export default{
		props: ['item', 'name', 'idIndex', 'index', 'editing'],
		data: function(){
			return {
				labelStyle: {
					'word-wrap': 'break-word',
					color: '',
				},
			}
		},
		methods: {
			delete_self() {
				this.$emit('itemDelete', this.index);
			},
		},
		computed: {
			value_to_show(){
				let tmp = '';
				if (Array.isArray(this.idIndex)) {
					for (let i = 0; i < this.idIndex.length; i++) {
						tmp = tmp + ' ' + this.realtimeData[this.idIndex[i]]['ResultStr'];
					}
				}
				else{
					tmp = this.realtimeData[this.idIndex]['ResultStr'];
				}
				if (this.item['min']&&this.item['max']) {
					let min = this.item['min'];
					let max = this.item['max'];
					min = parseFloat(min);
					max = parseFloat(max);
					let resultDouble = parseFloat(this.realtimeData[this.idIndex]['ResultStr']);
					if ( min<resultDouble||max>resultDouble ) {
						this.labelStyle['color'] = 'red';
					}
					else{
						this.labelStyle['color'] = '';
					}
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