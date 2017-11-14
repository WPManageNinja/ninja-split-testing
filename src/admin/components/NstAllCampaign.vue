<template>
	<div>
		<el-table
	      :data="tableData"
	      style="width: 100%">

	      <el-table-column
	        prop="no"
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
	        label="Status">
	        <template slot-scope="scope">
	        	<el-switch
	        		id="switch"
                    v-model="scope.row.active"
                    @change="campaignStatusChanged(scope.row.id, scope.row.active)"
                    on-text="Active"
  					off-text="Draft"
  					:width="75"
  					off-color="orange"
                    >
            	</el-switch>
	        </template>
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
	        	<el-button type="danger" size="mini" icon="delete2" @click="campaignDeleteDialogVisible(scope.row.id)"></el-button>
	        </template>
	      </el-table-column>
	    </el-table>

	    <el-dialog
		  title="Warning"
		  :visible.sync="deleteConfirmationDialog"
		  size="tiny"
		  >
		  <span>Are you sure, You want to delete this campaign?</span>
		  <span slot="footer" class="dialog-footer">
		    <el-button @click="deleteConfirmationDialog = false">Cancel</el-button>
		    <el-button type="primary" @click="confirmDeleteCampaign">Yes, Delete</el-button>
		  </span>
		</el-dialog>
	</div>
</template>

<script type="text/babel">
	import { forEach } from 'lodash';
	export default {
		name: 'NstAllCampaign',
		data() {
			return {
				deleteConfirmationDialog: false,
				tableData: [],
				deleteCampaignId: false
			}
		},
		methods: {
			campaignDeleteDialogVisible(id) {
				this.deleteCampaignId = id;
				this.deleteConfirmationDialog = true;
			},
			confirmDeleteCampaign() {
				jQuery.get(ajaxurl, {
					action: 'nst_routes', 
					target_action: 'delete-campaign-by-id',
					id: this.deleteCampaignId
				})
                .done((res) => {
                	this.fetchCampaignData();
                	this.$message({
                        showClose: true,
                        message: res.data.message,
                        type: 'success'
                    });
                    this.deleteConfirmationDialog = false;
                })
                .fail((error) => {
                    console.log(error);
                });
			},
			fetchCampaignData() {

				jQuery.get(ajaxurl, {
					action: 'nst_routes', 
					target_action: 'get-all-campaign' 
				})
                .done((res) => {
                    forEach(res.data.campaign, (campaign, key) => {
                    	campaign.no = key+1;
                        campaign.active = (campaign.status == 'active') ? true : false;
                    });
                    this.tableData = res.data.campaign;
                })
                .fail((error) => {
                    console.log(error);
                });
			},

			campaignStatusChanged(id, status) {
				jQuery.get(ajaxurl, {
                    action: 'nst_routes',
                    target_action: 'update-campaign-status',
                    update_status: {
                        id: id,
                        status: status ? 'active' : 'inactive'
                    }

                })
                    .done((res) => {
         				this.fetchCampaignData();
                        this.$message({
                            showClose: true,
                            message: res.data.message,
                            type: 'success'
                        });
                    })
                    .fail((err) => {
                        console.log(err)
                    })
			},
		},
		mounted() {
			this.componentEmit('navIndexing')
			this.fetchCampaignData();
		}
	}
</script>