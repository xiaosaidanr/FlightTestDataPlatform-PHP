<template>
	<div class="data_page">
		<grid-layout
			:layout="innerGroups['layout']"
			:col-num="12"
			:row-height="30"
			:is-draggable="draggable"
			:is-resizable="draggable"
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
					style="background: #eee;">
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
		props: ['groups', 'index', 'draggable'],
		components: {
			GridLayout,
			GridItem,
			group,
		},
		data: function(){
			return {
				innerGroups: this.groups,
				innerLayout: this.groups['layout'],
			}
		},
		methods: {
			onInnerGroupChanged(val) {
				this.innerGroups['children'][val['index']]['children'] = val['value'];
				this.$emit('innerPageChanged', {'value': this.innerGroups, 'index': this.index});
			},
			movedEvent: function(i, newX, newY){
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
				this.$emit('layoutChanged', {'layout': this.innerGroups['layout'], 'index': this.index});
			},
		},
		watch: {
			groups(val) {
				this.innerGroups = val;
			},
			innerGroups(val) {
				this.$emit('innerPageChanged', {'value': val, 'index': this.index});
			},
		},
	}
</script>