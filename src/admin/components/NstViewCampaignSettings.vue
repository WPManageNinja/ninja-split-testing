<template>
	<div class="nst_view_campaign_setttings m-l-20 m-t-10">
		<!--heading-->
		<el-row>
			<el-col :span="20" class="m-l-20">
				<el-card class="box-card">
					<div slot="header" class="clearfix">
					    <h3>Settings</h3>
					</div>
					<el-form :model="campaign">
					    <el-form-item label="Title">
					      <el-input v-model="campaign.title" auto-complete="off"></el-input>
					    </el-form-item>

						<nst_url_search v-model="selected_url" :title="'Select Your Target URL'"></nst_url_search>
					    
					 </el-form>
				    <el-button type="primary" icon="el-icon-arrow-right" @click="updateCampaign">Update</el-button>
				</el-card>
			</el-col>
		</el-row>
	</div>
</template>


<script>
	import NstUrlSearch from './NstUrlSearch.vue';
	export default {
		name:'nst_view_setting',
		props: ['campaign'],
		components: {
            'nst_url_search': NstUrlSearch
        },
		data() {
			return {
				selected_url : {}
			}
		},
		watch: {
			campaign() {
				this.setSelectedUrl();
			}
		},
		methods: {
			setSelectedUrl() {
				this.$set(this.selected_url, 'permalink', this.campaign.target_url);
				this.$set(this.selected_url, 'post_id', this.campaign.post_id);
			},
			updateCampaign() {
				jQuery.post(ajaxurl, {
					action: 'routes', 
					target_action: 'update-campaign',
					id: this.campaign.id,
					title : this.campaign.title, 
					post_id: this.selected_url.post_id,
					permalink: this.selected_url.permalink
				})
                .done((res) => {
                	this.editCampaignVisible = false;
                    this.$message({
                            showClose: true,
                            message: res.data.message,
                            type: 'success'
                        });
                    this.$router.push({
                    	name: 'nst_view_testing_page',
                    	params: {
                    		id: this.campaign.id
                    	}
                    })
                    this.$emit('setNavIndexing');
                })
                .fail((error) => {
                    this.$message({
                        showClose: true,
                        message: error.responseJSON.data.message,
                        type: 'error'
                    });
                });
			},
		},
		mounted() {
			this.setSelectedUrl();
		}
	}
</script>

<style lang="scss" scoped>
	.post-type-2 {
		.el-select {
			width:100%;
		}
	}
	
</style>




