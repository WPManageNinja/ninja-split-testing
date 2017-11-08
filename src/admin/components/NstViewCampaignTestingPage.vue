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
						<h2> <i class="el-icon-document"></i> Testing Pages</h2>
					</el-col>
					<el-col :span="12">
						<div class="right">
							<el-button type="primary" @click="dialogVisible = true" icon="plus"> Add New</el-button>
						</div>
					</el-col>
				</el-row>

				<hr>

				<div class="modal m-t-35">
					<el-dialog
					  title="Add a new testing page"
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
										      v-for="(item, index) in post_types"
										      :key="post_types[index]"
										      :label="post_types[index]"
										      :value="post_types[index]">
										    </el-option>
										</el-select>
									</div>
							    </el-form-item>
							</el-col>

							<el-col :span="17">
						      	<div class="post-type-2 m-t-35">
									<el-select v-model="campaign_url_data.target_post" placeholder="Select">
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

					    <div class="block m-t-10">
					    	<span class="demonstration">Traffic Split Amount</span>
							<el-slider
						      v-model="campaign_url_data.traffic_split_amount"
						      :step="1"
						      :max="10"
						      show-stops>
						    </el-slider>
						</div>
						
					    
					  </el-form>
					  <span slot="footer" class="dialog-footer">
					    <el-button @click="dialogVisible = false">Cancel</el-button>
					    <el-button type="primary" icon="el-icon-arrow-right" @click="addTestingPage">Add</el-button>
					  </span>
					</el-dialog>
				</div>

				<div class="modal m-t-35">
					<el-dialog
					  title="Add a new testing page"
					  :visible.sync="editDialogVisible"
					  width="30%"
					  :before-close="editHandleClose">
					  <el-form :model="edit_data">
					    <el-form-item label="Title">
					      <el-input v-model="edit_data.title" auto-complete="off"></el-input>
					    </el-form-item>

						<el-row>
							<el-col :span="7">
								<el-form-item label="Post Type :">
							      	<div class="post-type-1">
										<el-select v-model="post_type_value" placeholder="Select Post Type" @change="fetchAllPostAndPage">
										    <el-option
										      v-for="(item, index) in post_types"
										      :key="post_types[index]"
										      :label="post_types[index]"
										      :value="post_types[index]">
										    </el-option>
										</el-select>
									</div>
							    </el-form-item>
							</el-col>

							<el-col :span="17">
						      	<div class="post-type-2 m-t-35">
									<el-select v-model="edit_data.target_post" placeholder="Select">
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

					    <div class="block m-t-10">
					    	<span class="demonstration">Traffic Split Amount</span>
							<el-slider
						      v-model="edit_data.traffic_split_amount"
						      :step="1"
						      :max="10"
						      show-stops>
						    </el-slider>
						</div>
						
					    
					  </el-form>
					  <span slot="footer" class="dialog-footer">
					    <el-button @click="editDialogVisible = false">Cancel</el-button>
					    <el-button type="primary" icon="el-icon-arrow-right" @click="updateTestingPage">Update</el-button>
					  </span>
					</el-dialog>
				</div>


				<el-row :gutter="30">
					<el-col :md="12" :sm="24" :key="key" v-for="(item, key) in testing_pages" 
						class="m-b-20">
						<el-card class="box-card">
						  <div slot="header" class="clearfix">
						    <span class="label text_elipsis"><i class="el-icon-edit action" @click="setEditDialogVisible(item)"></i>  {{item.title}}</span>
						 
						    <el-switch
							    v-model="testing_pages[key].active"
							    style="float: right"
							    @change="testingPageStatusChanged(item.id, item.active)"
							   >
							</el-switch>
						  </div>
						  <div>
						  	<table class="table">
						  		<tbody>
						  			<tr>
						  				<td class="label">Title</td>
						  				<td class="data text_elipsis td-char-limit">{{item.title}}</td>
						  			</tr>

						  			<tr>
						  				<td class="label">URL</td>
						  				<td class="data text_elipsis td-char-limit"><a :href="item.target_url">{{item.target_url}}</a></td>
						  			</tr>
						  			<tr>
						  				<td class="label">Publish</td>
						  				<td class="data text_elipsis td-char-limit">{{item.created_at}}</td>
						  			</tr>
						  		</tbody>
						  	</table>
						  </div>
						</el-card>
					</el-col>
				</el-row>
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
				value5: true,
				dialogVisible: false,
				campaign_url_data: {
					target_post: '',
					traffic_split_amount: 0
				},
				post_type_value: 'post',
				post_types: [],
				postOrPage: [],
				post_id: '',
				slide_value: 0,
				testing_pages: [],
				editDialogVisible: false,
				edit_data: {
					target_post: '',
					traffic_split_amount: 0
				},
			}
		},
		methods: {
			setEditDialogVisible(item) {
				this.editDialogVisible = true
				this.edit_data = {
					id: item.id,
					title: item.title,
					target_post: '',
					traffic_split_amount: parseInt(item.traffic_split_amount)
				};
			},
			testingPageStatusChanged(id, status) {

				var self = this;

				jQuery.get(ajaxurl, {
					action: 'routes',
					target_action: 'update-testing-page-status',
					update_status : {
						id: id,
						status: status ? 'active' : 'inactive'
 					}
					
				})
				.done((res) => {
					self.fetchAllTestingPage();
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
			addTestingPage() {
				this.campaign_url_data.campaign_id = parseInt(this.$route.params.id);
				if(this.campaign_url_data.target_post) {
					let targetPostIndex = _.findIndex(this.postOrPage, {ID: this.campaign_url_data.target_post})
					this.campaign_url_data.target_post_id = this.campaign_url_data.target_post;
					this.campaign_url_data.target_url = this.postOrPage[targetPostIndex].guid;
					delete this.campaign_url_data['target_post'];
				}
				
				var self = this;

				jQuery.post(ajaxurl,{
					action: 'routes',
					target_action: 'store-campaign-testing-page',
					data: {
						...this.campaign_url_data
					}
				})
				.done((res) => {
					this.$message({
                        showClose: true,
                        message: res.data.message,
                        type: 'success'
                    });

					self.fetchAllTestingPage();
                    self.handleClose();

                    self.campaign_url_data = {
						target_post: '',
						traffic_split_amount: 0
					};
				})
				.fail((error) => {
					this.$message({
                        showClose: true,
                        message: error.responseJSON.data.message,
                        type: 'error'
                    });
				})
			},
			editHandleClose() {
				this.editDialogVisible = false;
			},
			handleClose() {
				this.dialogVisible = false
			},
			
			fetchAllTestingPage() {
				var self = this;

				jQuery.get(ajaxurl,{
					action: 'routes',
					target_action: 'get-all-testing-page',
					campaign_id: this.$route.params.id
				})
				.done((res) => {
					_.forEach(res.data, (item) => {
						if(item.status == 'active') {
							item.active = true
						} else {
							item.active = false
						}
					});
					self.testing_pages = res.data;
				})
				.fail((err) => {
					console.log(err)
				})
			},

			fetchAllPostTypes() {
				var self = this;
				jQuery.get(ajaxurl,{
					action: 'routes',
					target_action: 'get-all-post-types',
				})
				.done((res) => {
					self.post_types = res.data.post_types
				})
				.fail((err) => {
					console.log(err)
				})
			},
			fetchAllPostAndPage() {
				var self = this;
				self.post_id = ''
				jQuery.get(ajaxurl,{
					action: 'routes',
					target_action: 'get-all-post-and-pages',
					post_type: self.post_type_value.toLowerCase()
				})
				.done((res) => {
					self.postOrPage = res.data
				})
				.fail((err) => {
					console.log(err)
				})
			},
			updateCampaign() {

			},

			updateTestingPage() {
				this.edit_data.campaign_id = parseInt(this.$route.params.id);
				if(this.edit_data.target_post) {
					let targetPostIndex = _.findIndex(this.postOrPage, {ID: this.edit_data.target_post})
					this.edit_data.target_post_id = this.edit_data.target_post;
					this.edit_data.target_url = this.postOrPage[targetPostIndex].guid;
					delete this.edit_data['target_post'];
				}

				var self = this;

				jQuery.post(ajaxurl,{
					action: 'routes',
					target_action: 'store-campaign-testing-page',
					data: {
						...this.edit_data
					}
				})
				.done((res) => {
					this.$message({
                        showClose: true,
                        message: res.data.message,
                        type: 'success'
                    });

					self.fetchAllTestingPage();
                    self.editHandleClose();

                    self.edit_data = {
						target_post: '',
						traffic_split_amount: 0
					};
				})
				.fail((error) => {
					this.$message({
                        showClose: true,
                        message: error.responseJSON.data.message,
                        type: 'error'
                    });
				})
			}
		},
		mounted() {
			this.fetchAllTestingPage();
			this.fetchAllPostTypes();
			this.fetchAllPostAndPage();
		}
	}
</script>

<style lang="scss">
	.post-type-1 {
		margin-right:10px;
	}
	.post-type-2 {
		.el-select {
			width:100%;
		}
	}
	
	.modal {
		.el-dialog__wrapper{
			.el-dialog {
				padding:20px;
			}
		} 
	}
	.label {
		width:80px;
		font-size:15px;
		color:black;
		font-weight:500;
		padding: 6px 0px;

	}
	.data {
		font-size:15px;
		color:black;
		font-weight:300;
	}
	.settings {
		float: right;
	    font-size: 20px;
	    margin-left: 10px;
	    margin-top: 2px;
	}
	.el-switch {
		.el-switch__input {
			display: none;
		}
	}
	
</style>