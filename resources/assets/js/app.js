
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('vue2-animate/dist/vue2-animate.min.css')

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import VueHighcharts from 'vue-highcharts';
import highcharts from 'highcharts';
import VueRouter from 'vue-router'
import Vuex from 'vuex'
import routes from './routes'
// import AMap from 'vue-amap';
import PulseLoader from 'vue-spinner/src/PulseLoader.vue'


highcharts.setOptions({
	global: {
		useUTC: false
	}
});

// Vue.component('example', require('./components/Example.vue'));

Vue.use(VueHighcharts);
Vue.use(VueRouter);
Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        count: 0
    },
    mutations: {
        increment (state){
            state.count++
        }
    }
});

const router = new VueRouter({
    routes
})

const app = new Vue({
    el: '#app',
    store,
    data: {
    	loading: false,
    },
    components: {
    	PulseLoader,
    },
    router,
    created: function(){

    },
});

export default store;