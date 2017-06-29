<template>
	<div class="col-md-6 col-sm-6 data_item" style="padding: 0px;">
		<div class="col-md-7 col-sm-6 data_item_name" style="padding: 0px;">
			<label style="word-wrap: break-word;
						  background-color: #8eb4cb;
						  color: #fff;
						  border-radius: .25em;
						  padding: .2em .6em .3em
						  font-size: 75%;">{{ name }}</label>
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
						if (!this.realtimeData[this.idIndex[i]]) {
							return '';
						}
						tmp = tmp + ' ' + this.realtimeData[this.idIndex[i]]['ResultStr'];
					}
				}
				else{
					if (!this.realtimeData[this.idIndex]) {
						return '';
					}
					if (this.realtimeData[this.idIndex]['Unit'] == 'ms') {
						let time_total_ms = parseInt(this.realtimeData[this.idIndex]['ResultStr']);
						let time_total_s = parseInt(time_total_ms/1000);
						let time_h = parseInt(time_total_s/3600);
						let time_h_24 = 0;

						if (this.idIndex == 'FP_FLI_CTL_COM_SYS_TIME_0' || this.idIndex == 'FP_COLLECTOR_CTL_COM_SYS_TIME_0') {
							time_h_24 = time_h%24;
						}
						else{
							time_h_24 = (time_h + 8)%24;
						}
						let time_m = parseInt((time_total_s - time_h * 3600)/60);
						let time_s = time_total_s - time_h * 3600 - time_m * 60;
						tmp = time_h_24 + ':' + time_m + ':' + time_s;
					}
					else{
						tmp = this.realtimeData[this.idIndex]['ResultStr'] + this.realtimeData[this.idIndex]['Unit'];
					}
				}
				if (('min' in this.item)&&('max' in this.item)) {
					let min = this.item['min'];
					let max = this.item['max'];
					let amplifier = 1.0;
					if ( (min == '-1.5707963' || min == '-3.1415926' || min == '-6.2831852') || (max == '1.5707963' || max == '3.1415926' || max == '6.2831852') ) {
						amplifier = 180/3.1415926;
					}
					min = parseFloat(min) * amplifier;
					max = parseFloat(max) * amplifier;
					let resultDouble = parseFloat(this.realtimeData[this.idIndex]['ResultDouble']);
					if ( min>resultDouble||max<resultDouble ) {
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