<template>
	<div class="data_group">
		<div class="panel panel-default panel-primary" style="margin-bottom: 0px;">
			<div class="panel-heading" style="background-color:;border-color:;">
				<h3 class="panel-title">{{ name }}</h3>
			</div>
		</div>
		<div class="panel-body col-md-12 col-sm-12 data_group_value" style="padding: name0px;">
			<draggable v-model="innerItems" :options="{ group:name }" @start="drag=false" @end="drag=false">
				<transition-group name="fade" tag="div">
					<item v-for="item, key in innerItems" :item='item' :name="item['name']" 
					:idIndex="item['id_index']" key='item' :editing="editing" @itemDelete.capture="onItemDelete" :index="key" :width="item['width']"/>
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
				this.$emit('innerGroupChanged', {'value': val, 'index': this.index});
			}
		}
	}
</script>