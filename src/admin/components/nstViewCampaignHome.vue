<template >
	<div class="m-r-50">
		<el-row>
			<el-col :span="12" >
				<div class="inline-block">
					<h2 class="m-l-20"><i class="el-icon-edit action" @click="dialogVisible = true"></i> {{campaign_data.title}}</h2>
				</div>
			</el-col>
			<el-col :span="12">
				<div class="right">
					<!-- <el-button type="primary" @click="back()">Back</el-button> -->
				</div>
			</el-col>
		</el-row>
		

		<div>
			<el-dialog
			  title="Update Campaign"
			  :visible.sync="dialogVisible"
			  width="30%"
			  :before-close="handleClose">
			  <el-form :model="campaign_data">
			    <el-form-item label="Title">
			      <el-input v-model="campaign_data.title" auto-complete="off"></el-input>
			    </el-form-item>

				<label>Description</label>	
			    <quill-editor class="m-t-10" :content="campaign_data.description"
                      :options="editorOption"
                      @change="onEditorChange($event)">
                </quill-editor>
			    
			  </el-form>
			  <span slot="footer" class="dialog-footer">
			    <el-button @click="dialogVisible = false">Cancel</el-button>
			    <el-button type="primary" icon="el-icon-arrow-right" @click="updateCampaign">Update</el-button>
			  </span>
			</el-dialog>
		</div>
		

		<el-menu :default-active="activeIndex" class="el-menu-demo" mode="horizontal">
		  <router-link :to="{name: 'nst_view_testing_page'}"> 
		  	<el-menu-item index="1">Testing Pages</el-menu-item>
		  </router-link>
		  <router-link :to="{name: 'nst_view_analytics_page'}"> 
		  	<el-menu-item index="2">Analytics</el-menu-item>
		  </router-link>
		  <router-link :to="{name: 'nst_view_settings_page'}"> 
		  	<el-menu-item index="3">Settings</el-menu-item>
		  </router-link>
		</el-menu>

		<router-view @settingCompleted= "settingCompleted" :campaign="campaign_data" :loading="loading"></router-view>
	</div>
</template>

<script type="text/babel">
	export default {
		name: 'nst_view_campaign',
		data(){
			return {
				loading: true,
				editorOption: {
                    modules: {
                        toolbar: [
                            ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
                            ['blockquote', 'code-block'],

                            [{ 'header': 1 }, { 'header': 2 }],               // custom button values
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
                            [{ 'align': [] }],
                            [{ 'direction': 'rtl' }]
                        ]
                    }
                },
                campaign_data: {},
				dialogVisible: false,
				activeIndex: '1',
				form: {},
				navIndex: {
					nst_view_testing_page: '1',
					nst_view_analytics_page: '2',
					nst_view_settings_page: '3',
				},
			}
		},
		methods: {
			back(){
				this.$router.push({
					name:'nst_home'
				})
			},

			updateCampaign() {

				var self = this;
				jQuery.post(ajaxurl, {
					action: 'routes', 
					target_action: 'add-campaign',
					id : self.campaign_data.id,
					title : self.campaign_data.title, 
					description : self.form.description
				})
                .done((res) => {
                	self.dialogVisible = false;
                    this.$message({
                            showClose: true,
                            message: res.data.message,
                            type: 'success'
                        });
                })
                .fail((error) => {
                    this.$message({
                        showClose: true,
                        message: error.responseJSON.data.message,
                        type: 'error'
                    });
                });
			},

			setNavIndexing() {
				this.activeIndex = this.navIndex[this.$route.name];
			},
			fetchCampaignData() {
				var self = this;
				jQuery.get(ajaxurl,{
					action:'routes',
					target_action: 'get-campaign-by-id',
					campaign_id: this.$route.params.id
				})
				.done((res) => {
					self.loading = false;
					self.campaign_data = res.data
				})
				.fail((err) => {
					console.log(err)
				})
			},
			handleClose() {
				this.dialogVisible = false;
			},

			onEditorChange({text}) {
				console.log(text)
				this.form.description = text;
			},
			handleClick() {

			},

			settingCompleted() {
				this.fetchCampaignData();
			}
		},
		mounted() {
			this.setNavIndexing();
			this.fetchCampaignData()
		}
	}
</script>

<style lang="scss">

	.right {
		text-align: right;
		margin-top: 10px;
	}
</style>