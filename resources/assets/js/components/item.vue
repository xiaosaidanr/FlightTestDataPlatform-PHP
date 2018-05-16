<template>
	<div class="data_item" v-bind:class="[lg, md, sm, xs]" style="padding: 0px;">
	<!-- <div class="data_item" style="padding: 0px;"> -->
		<template v-if="name!=''&&idIndex!=''">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-center data_item_name" style="padding: 0px;">
<!-- 				<label style="word-wrap: break-word;
							  background-color: #8eb4cb;
							  color: #fff;
							  border-radius: .25em;
							  padding: .2em .6em .3em
							  font-size: 75%;">{{ name }}</label> -->
				<label style="word-wrap: break-word;
						  background-color: #8eb4cb;
						  color: #fff;
						  border-radius: .25em;
						  padding: 0px .3em 0px">{{ name }}</label>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-center data_item_value" style="padding: 0px;">
				<template v-if="editing">
					<button @click="delete_self">删除</button>
				</template>
				<template v-else>
					<label :style="labelStyle">{{ value_to_show }}</label>
				</template>
			</div>
		</template>
		<template v-else>
			<template v-if="name==''">
				<div class="col-md-12 col-sm-12 text-center data_item_value" style="padding: 0px;">
					<template v-if="editing">
						<button @click="delete_self">删除</button>
					</template>
					<template v-else>
						<label :style="[labelStyle, hiddenStyle]">{{ value_to_show }}</label>
					</template>
				</div>
			</template>
			<template v-else>
				<div class="col-md-12 col-sm-12 text-center data_item_name" style="padding: 0px;">
<!-- 					<label style="word-wrap: break-word;
								  background-color: #8eb4cb;
								  color: #fff;
								  border-radius: .25em;
								  padding: .2em .6em .3em
								  font-size: 75%;">{{ name }}</label> -->
					<label style="word-wrap: break-word;
						  background-color: #8eb4cb;
						  color: #fff;
						  border-radius: .25em;
						  padding: 0px .3em 0px">{{ name }}</label>
				</div>
			</template>
		</template>
	</div>
</template>
<script>
	import { mapState } from 'vuex'
	export default{
		props: ['item', 'name', 'idIndex', 'index', 'editing', 'width'],
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
			hiddenStyle(){
				if (('hidden' in this.item)) {
					return {
						visibility: 'hidden',
					};
				}
				else{
					return {
						visibility: 'visible',
					};
				}
			},
			lg(){
				return 'col-lg-' + this.width;
			},
			md(){
				return 'col-md-' + this.width;
			},
			sm(){
				return 'col-sm-' + this.width;
			},
			xs(){
				return 'col-xs-' + this.width;
			},
			value_to_show(){
				if (this.idIndex!='') {
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
								time_h_24 = time_h;
							}
							else{
								time_h_24 = (time_h + 8)%24;
							}
							let time_m = parseInt((time_total_s - time_h * 3600)/60);
							let time_s = time_total_s - time_h * 3600 - time_m * 60;
							tmp = time_h_24 + ':' + time_m + ':' + time_s;
						}
						else {
							if (this.idIndex == 'FP_GNSS_GPS_LON_0' || this.idIndex == 'FP_GNSS_GPS_LAT_0' || this.idIndex == 'FP_BIG_DIPPER_LON_0' || this.idIndex == 'FP_BIG_DIPPER_LON_1' || this.idIndex == 'FP_BIG_DIPPER_LAT_0' || this.idIndex == 'FP_BIG_DIPPER_LAT_1')
							{
								let float_data = parseFloat(this.realtimeData[this.idIndex]['ResultStr']);
								let str_data = float_data.toFixed(3);
								tmp = str_data + this.realtimeData[this.idIndex]['Unit'];
							}
							else{
								tmp = this.realtimeData[this.idIndex]['ResultStr'] + this.realtimeData[this.idIndex]['Unit'];
							}
						}
					}
					// if (('min' in this.item)&&('max' in this.item)) {
					if ((this.realtimeData[this.idIndex]['Max'])&&(this.realtimeData[this.idIndex]['Min'])) {
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
				}
				else{
					return '0';
				}
			},
			...mapState({
				realtimeData: state => state.realtimeData,
				realtimeDataAlias: 'realtimeData',
			})
		},
	}
</script>
