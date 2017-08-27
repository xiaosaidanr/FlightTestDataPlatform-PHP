<template>
	<div :id='test'>
		<div class="panel panel-default panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">
					航向角-主
				</h3>
			</div>
			<div class="panel-body">
				<highcharts :options='chartOption_main' ref="chart_main"></highcharts>
			</div>
			<div class="panel-heading">
				<h3 class="panel-title">
					航向角-备
				</h3>
			</div>
			<div class="panel-body">
				<highcharts :options='chartOption_reserved' ref="chart_reserved"></highcharts>
			</div>
			<div class="panel-heading">
				<h3 class="panel-title">
					俯仰角-备
				</h3>
			</div>
			<div class="panel-body">
				<highcharts :options='chartOption_fuyangjiaobei' ref="chart_fuyangjiaobei"></highcharts>
			</div>
		</div>
	</div> 
</template>
<script>
	import defaultOptions from '../chartOptions';
	import { mapState } from 'vuex';
	import _ from 'lodash';
	export default{
		data: function(){
			return {
				chartOption_main: _.cloneDeep(defaultOptions['default']),
				chartOption_reserved: _.cloneDeep(defaultOptions['default']),
				chartOption_fuyangjiaobei: _.cloneDeep(defaultOptions['default']),
			}
		},
		methods: {
			
		},
		created: function(){
			this.chartOption_main.series[0].name = '航向角-主';
			this.chartOption_reserved.series[0].name = '航向角-备';
			this.chartOption_fuyangjiaobei.series[0].name = '俯仰角-备';
		},
		computed: {
			test(){
				try{
					var data_main = this.realtimeData['FP_TRUE_COURSE_0'];
					var y_main = parseFloat(data_main['ResultStr']);
					var x_main = parseInt(data_main['Time'])*1000;
					console.log(this.$refs.chart_main.chart.series[0].data.length);
					if (this.$refs.chart_main.chart.series[0].data.length == 0) {
						this.$refs.chart_main.chart.series[0].addPoint([x_main, y_main], true, false);
					}
					else{
						if (this.$refs.chart_main.chart.series[0].data[this.$refs.chart_main.chart.series[0].data.length-1]['x']!=x_main) {
							if (x_main - this.$refs.chart_main.chart.series[0].data[0]['x'] >= 30 * 60 * 1000) {
								this.$refs.chart_main.chart.series[0].removePoint(0);
								this.$refs.chart_main.chart.series[0].addPoint([x_main, y_main], true, true);
							}
							else{
								this.$refs.chart_main.chart.series[0].addPoint([x_main, y_main], true, false);

							}
						}
					}
					var data_reserved = this.realtimeData['FP_TRUE_COURSE_1'];
					var y_reserved = parseFloat(data_reserved['ResultStr']);
					var x_reserved = parseInt(data_reserved['Time'])*1000;
					console.log(this.$refs.chart_reserved.chart.series[0].data.length);
					if (this.$refs.chart_reserved.chart.series[0].data.length == 0) {
						this.$refs.chart_reserved.chart.series[0].addPoint([x_reserved, y_reserved], true, false);
					}
					else{
						if (this.$refs.chart_reserved.chart.series[0].data[this.$refs.chart_reserved.chart.series[0].data.length-1]['x']!=x_reserved) {
							if (x_reserved - this.$refs.chart_reserved.chart.series[0].data[0]['x'] >= 30 * 60 * 1000) {
								this.$refs.chart_reserved.chart.series[0].removePoint(0);
								this.$refs.chart_reserved.chart.series[0].addPoint([x_reserved, y_reserved], true, true);
							}
							else{
								this.$refs.chart_reserved.chart.series[0].addPoint([x_reserved, y_reserved], true, false);

							}
						}
					}


					var data_fuyangjiaobei = this.realtimeData['FP_PITCH_1'];
					var y_fuyangjiaobei = parseFloat(data_fuyangjiaobei['ResultStr']);
					var x_fuyangjiaobei = parseInt(data_fuyangjiaobei['Time'])*1000;
					console.log(this.$refs.chart_fuyangjiaobei.chart.series[0].data.length);
					if (this.$refs.chart_fuyangjiaobei.chart.series[0].data.length == 0) {
						this.$refs.chart_fuyangjiaobei.chart.series[0].addPoint([x_fuyangjiaobei, y_fuyangjiaobei], true, false);
					}
					else{
						if (this.$refs.chart_fuyangjiaobei.chart.series[0].data[this.$refs.chart_fuyangjiaobei.chart.series[0].data.length-1]['x']!=x_fuyangjiaobei) {
							if (x_fuyangjiaobei - this.$refs.chart_fuyangjiaobei.chart.series[0].data[0]['x'] >= 30 * 60 * 1000) {
								this.$refs.chart_fuyangjiaobei.chart.series[0].removePoint(0);
								this.$refs.chart_fuyangjiaobei.chart.series[0].addPoint([x_fuyangjiaobei, y_fuyangjiaobei], true, true);
							}
							else{
								this.$refs.chart_fuyangjiaobei.chart.series[0].addPoint([x_fuyangjiaobei, y_fuyangjiaobei], true, false);

							}
						}
					}
					return 0;
				}
				catch (e){
					console.log(e);
					return 0;
				}
				
			},
			...mapState({
				realtimeData: state => state.realtimeData,
				realtimeDataAlias: 'realtimeData',
			})
		}
	}
</script>