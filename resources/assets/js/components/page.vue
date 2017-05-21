<template>
	<div class="data_page">
		<div class = "checkbox">
			<label><input type = "checkbox" v-model="draggable">Enable drag and drop</label>
		</div>
		<grid-layout
			:layout="innerGroups['layout']"
			:col-num="12"
			:row-height="30"
			:is-draggable="draggable"
			:is-resizable="true"
			:vertical-compact="true"
			:margin="[10, 10]"
			:use-css-transforms="true">
			<grid-item v-for="group, index in innerGroups['children']"
					:x="innerGroups['layout'][index].x"
					:y="innerGroups['layout'][index].y"
					:w="innerGroups['layout'][index].w"
					:h="innerGroups['layout'][index].h"
					:i="innerGroups['layout'][index].i"
					@resized="resizedEvent"
					@moved="movedEvent"
					style="background: #eee;border: 1px solid black;">
				<group :name="group['name']" :index="index" :items="group['children']" @innerGroupChanged.capture="onInnerGroupChanged"/>
			</grid-item>
		</grid-layout>
	</div>
</template>
<script>
	import VueGridLayout from 'vue-grid-layout'
	import group from '../components/group.vue'

	var GridLayout = VueGridLayout.GridLayout;
	var GridItem = VueGridLayout.GridItem;
	export default{
		props: ['groups', 'index'],
		components: {
			GridLayout,
			GridItem,
			group,
		},
		data: function(){
			return {
				draggable: false,
				innerGroups: this.groups,
				innerLayout: this.groups['layout'],
			}
		},
		methods: {
			onInnerGroupChanged(val) {
				// alert('event from group');
				this.innerGroups['children'][val['index']]['children'] = val['value'];
				this.$emit('innerPageChanged', {'value': this.innerGroups, 'index': this.index});
			},
			movedEvent: function(i, newX, newY){
				// console.log("MOVED i=" + i + ", X=" + newX + ", Y=" + newY);
				// console.log(this.innerGroups['layout']);
				this.$emit('layoutChanged', {'layout': this.innerGroups['layout'], 'index': this.index});
			},
			/**
			* 
			* @param i the item id/index
			* @param newH new height in grid rows 
			* @param newW new width in grid columns
			* @param newHPx new height in pixels
			* @param newWPx new width in pixels
			* 
			*/
			resizedEvent: function(i, newH, newW, newHPx, newWPx){
				// console.log("RESIZED i=" + i + ", H=" + newH + ", W=" + newW + ", H(px)=" + newHPx + ", W(px)=" + newWPx);
				// console.log(this.innerGroups['layout']);
				this.$emit('layoutChanged', {'layout': this.innerGroups['layout'], 'index': this.index});
			},
		},
		watch: {
			groups(val) {
				this.innerGroups = val;
			},
			innerGroups(val) {
				// alert('here');
				this.$emit('innerPageChanged', {'value': val, 'index': this.index});
			},
		},
	}
</script>