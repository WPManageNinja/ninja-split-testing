<template>
	<div>
		<el-table
	      :data="tableData"
	      style="width: 100%">

	      <el-table-column
	        prop="id"
	        label="No."
	        width="100"
	        >
	      </el-table-column>

	      <el-table-column
	        prop="title"
	        label="Title"
	       >
	      </el-table-column>

	      <el-table-column
	        prop="status"
	        label="Status">
	      </el-table-column>

	      <el-table-column
	        prop="created_at"
	        label="Created at">
	      </el-table-column>

	      <el-table-column
	        label="Actions">
	        <template slot-scope="scope">
	        	<router-link :to="{ name: 'nst_view_testing_page', params: { id: scope.row.id }}">
	        		<el-button type="primary" size="mini" icon="view"></el-button>
	        	</router-link>
	        	<el-button type="danger" size="mini" icon="delete2"></el-button>
	        </template>
	      </el-table-column>

	    </el-table>
	</div>
</template>

<script type="text/babel">
	export default {
		name: 'NstAllCampaign',
		data() {
			return {
				tableData: []
			}
		},
		methods: {
			fetchCampaignData() {
				
				var self = this;

				jQuery.get(ajaxurl, {
					action: 'routes', 
					target_action: 'get-all-campaign' 
				})
                .done((res) => {
                    self.tableData = res.data;
                })
                .fail((error) => {
                    console.log(error);
                });
			},
		},
		mounted() {
			this.componentEmit('navIndexing')
			this.fetchCampaignData();
		}
	}
</script>