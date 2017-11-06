<template>
	<div class="nst_view_testing_page">
		<el-row v-loading="loading">
			<div class="m-l-20 m-t-10 m-l-10" v-if="!campaign.post_id && !loading">
				<el-alert
			    	title="Please add a post or page to this campaign!"
			    	type="info">
			  	</el-alert>
			</div>
			<div class="m-l-20 m-t-10" v-if="campaign.post_id && !loading">
				<el-row>
					<el-col :span="12" >
						<h2>Testing Pages</h2>
					</el-col>
					<el-col :span="12">
						<div class="right">
							<el-button type="primary" @click="dialogVisible = true">Add New</el-button>
						</div>
					</el-col>
				</el-row>

				<div>
					<el-dialog
					  title="Update Campaign"
					  :visible.sync="dialogVisible"
					  width="30%"
					  :before-close="handleClose">
					  <el-form :model="campaign_url_data">
					    <el-form-item label="Title">
					      <el-input v-model="campaign_url_data.title" auto-complete="off"></el-input>
					    </el-form-item>

						<el-row>
							<el-col :span="7">
								<el-form-item label="Post Type :">
							      	<div class="post-type-1">
										<el-select v-model="post_type_value" placeholder="Select Post Type" @change="fetchAllPostAndPage">
										    <el-option
										      v-for="item in post_types"
										      :key="item.value"
										      :label="item.label"
										      :value="item.value">
										    </el-option>
										</el-select>
									</div>
							    </el-form-item>
							</el-col>

							<el-col :span="8">
						      	<div class="post-type-2 m-t-35">
									<el-select v-model="post_id" placeholder="Select">
									    <el-option
									      v-for="item in postOrPage"
									      :key="item.title"
									      :label="item.post_title"
									      :value="item.ID">
									    </el-option>
									</el-select>
								</div>
							</el-col>
						</el-row>

					    

						
					    
					  </el-form>
					  <span slot="footer" class="dialog-footer">
					    <el-button @click="dialogVisible = false">Cancel</el-button>
					    <el-button type="primary" icon="el-icon-arrow-right" @click="updateCampaign">Update</el-button>
					  </span>
					</el-dialog>
				</div>

				<el-col :span="10">
					<el-card class="box-card">
					  <div slot="header" class="clearfix">
					    <span>Card name</span>
					    <el-button style="float: right; padding: 3px 0" type="text">Edit</el-button>
					  </div>
					  <div>
					  	<p>Url: www.xyz.com</p>
					  	<p>Title: Title 1</p>
					  	<p>Date: 2019-09-09</p>
					  </div>
					</el-card>
				</el-col>

				<el-col :span="9" class="m-b-20 m-l-20">
					<el-card class="box-card">
					  <div slot="header" class="clearfix">
					    <span>Card name</span>
					    <el-button style="float: right; padding: 3px 0" type="text">Edit</el-button>
					  </div>

					  <div>
					  	<p>Url: www.xyz.com</p>
					  	<p>Title: Title 1</p>
					  	<p>Date: 2019-09-09</p>
					  </div>

					</el-card>
				</el-col>

				<el-col :span="10">
					<el-card class="box-card">
					  <div slot="header" class="clearfix">
					    <span>Card name</span>
					    <el-button style="float: right; padding: 3px 0" type="text">Edit</el-button>
					  </div>
						
					  <div>
					  	<p>Url: www.xyz.com</p>
					  	<p>Title: Title 1</p>
					  	<p>Date: 2019-09-09</p>
					  </div>

					</el-card>
				</el-col>
			</div>
		</el-row>
	</div>
</template>

<script>
	export default {
		name: 'nst_view_campaign_testing_page',
		props: ['campaign', 'loading'],
		data() {
			return {
				dialogVisible: false,
				campaign_url_data: {}
			}
		},
		mounted() {
			console.log(this.data)
		}
	}
</script>

<style scoped>
	.post-type-2 {
		.el-select {
			width:100%;
		}
	}
</style>