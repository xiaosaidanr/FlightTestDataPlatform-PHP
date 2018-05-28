import highcharts from 'highcharts';
var defaultOption = {
	chart: {
		type: 'spline',
		animation: highcharts.svg,
		zoomType: 'x'
	},
	title: {
		text: ' '
	},
	xAxis: {
		type: 'datetime',
		minRange: 30 * 60 * 1000,
		minTickInterval: 60 * 1000
	},
	yAxis: {
		title: {
			text: ' '
		},
		plotLines: [{
			value: 0,
			width: 1,
			color: 'blue'
		}]
	},
	tooltip: {
		backgroundColor: '#fff',
		borderColor: 'black',
		formatter: function(){
              return '<b>' + this.series.name + '</b><br/>' +
              highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) + '<br/>' +
              highcharts.numberFormat(this.y, 2) + ' ' + '°';
            }
	},
	legend: {
		enabled: false
	},
	exporting: {
		enable: true
	},
	plotOptions: {
		spline: {
			lineWidth: 1,
			fillOpacity: 0.1,
			marker: {
				enable: true,
				states: {
					hover: {
						enable: true,
						radius: 1.5
					}
				}
			},
			shadow: false
		}
	},
	series: [{
		data: (function(){
			var data = [];
			// var time = (new Date()).getTime();
			// for(let i = -19; i <= 0; i += 1){
			// 	data.push({
			// 		x: time + i *1000,
			// 		y: 0
			// 	});
			// }
			return data;
		}())
	}],
};
export default {
	'default': defaultOption,
}
