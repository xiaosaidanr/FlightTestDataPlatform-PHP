<template>
	<div>
		<!-- <label>Under Construction</label> -->
		<table class="table" style="table-layout:fixed">
			<thead>
				<tr>
					<th width="10">编号</th>
					<th width="10">时间</th>
					<th width="80">信息</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="item, key in inner_message_array" :class="item.status">
					<td>{{ key + 1 }}</td>
					<td>{{ item.time }}</td>
					<td style="word-wrap: break-word;">{{ item.message }}</td>
				</tr>
			</tbody>			
		</table>
	</div>
</template>
<script>
	import { mapState } from 'vuex'
	export default{
		data: function(){
			return {
				inner_message_array: new Array(),
			}
		},
		methods: {
			
		},
		computed: {
			message_array(){
				// let message_item = this.realtimeData['info_message'];
				// let length = this.inner_message_array.length;
				// console.log(length);
				// return this.inner_message_array;
			},
			...mapState({
				realtimeData: state => state.realtimeData,
				realtimeDataAlias: 'realtimeData',
			})
		},
		watch: {
			realtimeData(val){
				let new_message_item = this.realtimeData['info_message'];
				let length = this.inner_message_array.length;
				// console.log(length);
				if ( length > 0 ) {
					let old_last_item = this.inner_message_array[length - 1];
					// console.log(old_last_item);
					if (old_last_item.id == new_message_item.id) {
						return;
					}
					else{
						if (length >= 10) {
							this.inner_message_array.shift();
							this.inner_message_array.push(new_message_item);
						}
						else{
							this.inner_message_array.push(new_message_item);
						}
					}
				}
				else{
					this.inner_message_array.push(new_message_item);
				}
			}
		}
	}
</script>

<!-- <template>
	<div>
		<label>Under Construction</label>
	</div>
</template>
<script>
	export default{
		data: function(){
			return {

			}
		},
		methods: {
			
		}
	}
</script> -->