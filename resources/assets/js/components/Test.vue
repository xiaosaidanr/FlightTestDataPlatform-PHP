<template>
    <div>
        <label>Test</label>
        <child v-bind:header="count"></child>
        <draggable v-model="Array1" :options="{group:'people'}" @start="drag=false" @end="drag=false">
        	<transition-group name="fade" tag="ul">
        		<li v-for="element in Array1" key='li'>{{element.name}}</li>
        	</transition-group>
        </draggable>
        <draggable v-model="Array2" :options="{group:'people'}" @start="drag=false" @end="drag=false">
        	<transition-group name="fade" tag="ul">
        		<li v-for="element in Array2" key='li'>{{element.name}}</li>
        	</transition-group>
        </draggable>
        <button @click="ddddddd">test</button>
        <button @click="addItem">add</button>
        <div class = "checkbox">
          <label><input type = "checkbox" v-model="draggable">Enable drag and drop</label>
        </div>
        <grid-layout
            :layout="layout"
            :col-num="12"
            :row-height="30"
            :is-draggable="draggable"
            :is-resizable="true"
            :vertical-compact="true"
            :margin="[10, 10]"
            :use-css-transforms="true">

	        <grid-item v-for="item in layout"
	                   :x="item.x"
	                   :y="item.y"
	                   :w="item.w"
	                   :h="item.h"
	                   :i="item.i"
	                   style="background: #eee;border: 1px solid black;">
	            <child v-bind:header="item.header"></child>
				<draggable v-model="Array2" :options="{group:'people'}" @start="drag=false" @end="drag=false">
					<transition-group name="fade" tag="ul">
						<li v-for="element in Array2" key='li'>{{element.name}}</li>
					</transition-group>
				</draggable>
				<button @click="on_edit_grid_item">edit</button>
	        </grid-item>
    	</grid-layout>
    </div>
</template>
<script>
	import draggable from 'vuedraggable'
	import VueGridLayout from 'vue-grid-layout'
	import { mapState } from 'vuex'
	// import store from '../app'
	var testLayout = [
	    {"x":0,"y":0,"w":2,"h":2,"i":"0","header":"test1"},
	    {"x":2,"y":0,"w":2,"h":4,"i":"1","header":"test2"},
	    {"x":4,"y":0,"w":2,"h":5,"i":"2","header":"test3"},
	    {"x":6,"y":0,"w":2,"h":3,"i":"3","header":"test4"},
	    {"x":8,"y":0,"w":2,"h":3,"i":"4","header":"test5"},
	    {"x":10,"y":0,"w":2,"h":3,"i":"5","header":"test6"},
	    {"x":0,"y":5,"w":2,"h":5,"i":"6","header":"test7"},
	    {"x":2,"y":5,"w":2,"h":5,"i":"7","header":"test8"},
	    {"x":4,"y":5,"w":2,"h":5,"i":"8","header":"test9"},
	    {"x":6,"y":4,"w":2,"h":4,"i":"9","header":"test10"},
	    {"x":8,"y":4,"w":2,"h":4,"i":"10","header":"test11"},
	    {"x":10,"y":4,"w":2,"h":4,"i":"11","header":"test12"},
	    {"x":0,"y":10,"w":2,"h":5,"i":"12","header":"test13"},
	    {"x":2,"y":10,"w":2,"h":5,"i":"13","header":"test14"},
	    {"x":4,"y":8,"w":2,"h":4,"i":"14","header":"test15"},
	    {"x":6,"y":8,"w":2,"h":4,"i":"15","header":"test16"},
	    {"x":8,"y":10,"w":2,"h":5,"i":"16","header":"test17"},
	    {"x":10,"y":4,"w":2,"h":2,"i":"17","header":"test18"},
	    {"x":0,"y":9,"w":2,"h":3,"i":"18","header":"test19"},
	    {"x":2,"y":6,"w":2,"h":2,"i":"19","header":"test20"}
	];
	var GridLayout = VueGridLayout.GridLayout;
	var GridItem = VueGridLayout.GridItem;
	var Child = {
		props: ['header'],
		template: '<label >{{ header }}</label>'
	}
	export default{
		data: function(){
			return {
				draggable: true,
				Array1:[
					{
						name: 'test1',
					},
					{
						name: 'test2'
					},
					{
						name: 'test3'
					},
					{
						name: 'test4'
					},
				],
				Array2:[
					{
						name: 'test5'
					},
					{
						name: 'test6'
					},
					{
						name: 'test7'
					},
					{
						name: 'test8'
					},
				],
				layout: testLayout,
			}
		},
		computed: mapState({
			count: state => state.count,
			countAlias: 'count',
		}),
		components: {
			draggable,
			GridLayout,
			GridItem,
			Child
		},
		methods: {
			ddddddd: function(){
				alert(this.Array1[1]['name']);
				this.$store.commit('increment');
				alert(this.count);
			},
			addItem: function(){
				alert('here');
				this.Array2.push({ 'name': 'test9' });
			},
			on_edit_grid_item: function(){
				alert('test');
			}
		},
	}
</script>