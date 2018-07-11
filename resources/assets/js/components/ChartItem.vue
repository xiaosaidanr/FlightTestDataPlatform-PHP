<template>
<div :class='updater'>
    <div class="panel panel-default panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">
					{{ chartItem['label'] }}
				</h3>
        </div>
        <div class="panel-body">
            <highcharts :options='chartOption' :ref="chartItem['id']"></highcharts>
        </div>
    </div>
</div>
</template>
<script>
import defaultOptions from '../chartOptions';
import {
    mapState
} from 'vuex';
import _ from 'lodash';
export default {
    props: ['index', 'chartItem'],
    data: function() {
        return {
            chartOption: _.cloneDeep(defaultOptions['default']),
        }
    },
    methods: {

    },
    computed: {
        updater() {
            try {
                var data = this.realtimeData[this.chartItem['id_index']];
                var y = parseFloat(data['ResultStr']);
                var x = parseInt(data['Time']) * 1000;
                if (isNaN(y) || isNaN(y)) {
                    console.log(this.chartItem['label'] + ":" + this.$refs[this.chartItem['id']].chart.series[0].data.length);
                    return 0
                }
                if (this.$refs[this.chartItem['id']].chart.series[0].data.length == 0) {
                    this.$refs[this.chartItem['id']].chart.series[0].addPoint([x, y], true, false);
                } else {
                    if (this.$refs[this.chartItem['id']].chart.series[0].data[this.$refs[this.chartItem['id']].chart.series[0].data.length - 1]['x'] != x) {
                        if (x - this.$refs[this.chartItem['id']].chart.series[0].data[0]['x'] >= 30 * 60 * 1000) {
                            this.$refs[this.chartItem['id']].chart.series[0].removePoint(0);
                            this.$refs[this.chartItem['id']].chart.series[0].addPoint([x, y], true, true);
                        } else {
                            this.$refs[this.chartItem['id']].chart.series[0].addPoint([x, y], true, false);

                        }
                    }
                }
                return 0;
            } catch (e) {
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
