<template>
	<!-- <div class="col-md-2 col-sm-2 data_group" style="padding: 0px;border:1px solid;"> -->
	<div class="data_group" style="padding: 0px;border:1px solid;">
		<div class="col-md-12 col-sm-12 data_group_name" style="padding: 0px;">
			<h2 style="word-wrap: break-word;">{{ name }}</h2>
		</div>
		<div class="col-md-12 col-sm-12 data_group_value" style="padding: name0px;">
			<!-- <item v-for="value, key in items" :name="key" :value="0"/> -->
			<draggable v-model="innerItems" :options="{ group:name }" @start="drag=false" @end="drag=false">
				<!-- <transition-group name="fade" tag="ul"> -->
				<transition-group name="fade" tag="div">
					<!-- <li v-for="element in innerItems" key='li'>{{element['name']}}</li> -->
					<item v-for="item, key in innerItems" :name="item['name']" 
					:idIndex="item['id_index']" key='item' :editing="editing" @itemDelete.capture="onItemDelete" :index="key"/>
				</transition-group>
			</draggable>
		</div>
		<!-- <button @click="editing = !editing;">{{ button_name }}</button> -->
	</div>
</template>
<script>
	import draggable from 'vuedraggable'
	import item from '../components/item.vue'
	export default{
		props: ['items', 'index', 'name'],
		data: function(){
			return {
				innerItems: this.items,
				editing: false,
			};
		},
		components: {
			item,
			draggable,
		},
		computed: {
			button_name() {
				return (this.editing==false)?"编辑":"取消";
			},
		},
		methods: {
			onItemDelete(index_str) {
				var index = parseInt(index_str);
				this.innerItems.splice(index, 1);
			},
		},
		watch: {
			items(val) {
				this.innerItems = val;
			},
			innerItems(val) {
				// if (val!=this.items) {
				// }
				this.$emit('innerGroupChanged', {'value': val, 'index': this.index});
			}
		}
	}
</script>