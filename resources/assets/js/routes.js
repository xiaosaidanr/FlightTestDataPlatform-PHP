import chart from './components/Chart.vue'
import config from './components/Config.vue'
import custom_data from './components/CustomData.vue'
import raw_data from './components/RawData.vue'
import test from './components/Test.vue'
import system_info from './components/SystemInfo.vue'
// import header from './components/Header.vue'

const routes = [
{
	path: '/',
	redirect: '/raw_data'
},
{
	path: '/chart',
	components: {
		default: chart,
		// header: header,
	},
	props: {
		header: {
			title: '数据曲线'
		}
	}
},
{
	path: '/config*',
	components: {
		default: config,
		// header: header,
	},
	props: {
		header: {
			title: '数据配置'
		}
	}
},
{
	path: '/custom_data',
	components: {
		default: custom_data,
		// header: header,
	},
	props: {
		header: {
			title: '定制数据'
		}
	}
},
{
	path: '/raw_data',
	components: {
		default: raw_data,
		// header: header,
	},
	props: {
		header: {
			title: '遥测数据'
		}
	}
},
{
	path: '/test',
	components: {
		default: test,
		// header: header,
	},
	props: {
		header: {
			title: '测试'
		}
	}
},
{
	path: '/system_info',
	components: {
		default: system_info,
		// header: header,
	},
	props: {
		header: {
			title: '遥控遥测状态'
		}
	}
},
]
module.exports = routes