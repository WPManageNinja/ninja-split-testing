<template>
	<div class="nst_view_campaign_setttings">
		<!--heading-->
		<el-row>
			<el-col :span="20" class="m-l-20">
				<el-card class="box-card">
					<div slot="header" class="clearfix">
					    <h3>Please specify your target url: </h3>
					</div>
					<el-form :model="form">
						<div class="element-block">
							<!--post type dropdown-->
							
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

							<el-col :span="8">
						      	<div class="post-type-2">
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
						</div>
						
						<el-col :span="3" :offset="1">
							<el-button type="primary" @click='submit'> Update </el-button>
						</el-col>

					</el-form>
				</el-card>
			</el-col>
		</el-row>

		
			
			<!--contents-->
		

	</div>
</template>


<script>
	export default {
		name:'nst_view_setting',
		props: ['campaign'],
		data() {
			return {
				form: {},
				post_type_value: 'post',
				post_types: [],
				postOrPage: [],
				post_id: ''
			}
		},
		methods: {
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
					console.log(self.postOrPage)
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
					console.log(res)
				})
				.fail((err) => {
					console.log(err)
				})
			},
			submit() {
				var self = this;
				jQuery.post(ajaxurl, {
					action: 'routes', 
					target_action: 'add-campaign-post-id',
					id : parseInt(self.campaign.id),
					post_id: parseInt(self.post_id) ? parseInt(self.post_id) : ''
				})
                .done((res) => {
                    this.$message({
                            showClose: true,
                            message: res.data.message,
                            type: 'success'
                        });

                    self.$emit('settingCompleted');
                })
                .fail((error) => {
                    this.$message({
                        showClose: true,
                        message: error.responseJSON.data.message,
                        type: 'error'
                    });
                });
			}
		},
		mounted() {
			this.fetchAllPostTypes();
			this.fetchAllPostAndPage();

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




