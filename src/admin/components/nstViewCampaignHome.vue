<template >
	<div class="m-r-20">
		<el-row>
			<el-col :span="12" >
				<div class="inline-block">
					<h2><i class="el-icon-edit action" @click="setEditCampaignVisible"></i> {{campaign_data.title}}</h2>
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
			  :visible.sync="editCampaignVisible"
			  width="30%"
			  :before-close="handleClose">
			  <el-form :model="campaign_data">
			    <el-form-item label="Title">
			      <el-input v-model="campaign_data.title" auto-complete="off"></el-input>
			    </el-form-item>

				<nst_url_search v-model="selected_url" :title="'Select Your Target URL'"></nst_url_search>
			    
			  </el-form>
			  <span slot="footer" class="dialog-footer">
			    <el-button @click="editCampaignVisible = false">Cancel</el-button>
			    <el-button type="primary" icon="el-icon-arrow-right" @click="updateCampaign">Update</el-button>
			  </span>
			</el-dialog>
		</div>
		

		<el-menu :default-active="activeIndex" class="el-menu-demo bg-w" mode="horizontal">
		  <router-link :to="{name: 'nst_view_testing_page'}"> 
		  	<el-menu-item index="1" :style="activeIndex == '1' ? 'border-bottom: 5px solid #20a0ff' : ''">
		  		Testing Pages
		  	</el-menu-item>
		  </router-link>
		  <router-link :to="{name: 'nst_view_analytics_page'}"> 
		  	<el-menu-item index="2" :style="activeIndex == '2' ? 'border-bottom: 5px solid #20a0ff' : ''">
		  		Analytics
		  	</el-menu-item>
		  </router-link>
		  <router-link :to="{name: 'nst_view_settings_page'}"> 
		  	<el-menu-item index="3" :style="activeIndex == '3' ? 'border-bottom: 5px solid #20a0ff' : ''">
		  		Settings
		  	</el-menu-item>
		  </router-link>
		</el-menu>

		<router-view @setNavIndexing="setNavIndexing" @settingCompleted= "settingCompleted" :campaign="campaign_data" :loading="loading"></router-view>
	</div>
</template>

<script type="text/babel">
	import NstUrlSearch from './NstUrlSearch.vue';
	export default {
		name: 'nst_view_campaign',
		components: {
		    'nst_url_search' : NstUrlSearch
		},
		data(){
			return {
				loading: true,
				selected_url: {},
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
				editCampaignVisible: false,
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
			setEditCampaignVisible() {
				this.selected_url = {
					post_id: this.campaign_data.post_id,
					permalink: this.campaign_data.target_url,
				}
				this.editCampaignVisible =  true
			},
			updateCampaign() {
				jQuery.post(ajaxurl, {
					action: 'nst_routes', 
					target_action: 'update-campaign',
					id: this.campaign_data.id,
					title : this.campaign_data.title, 
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
				console.log('calla')
				this.activeIndex = this.navIndex[this.$route.name];
			},
			fetchCampaignData() {
				jQuery.get(ajaxurl,{
					action:'nst_routes',
					target_action: 'get-campaign-by-id',
					campaign_id: this.$route.params.id
				})
				.done((res) => {
					this.loading = false;
					this.campaign_data = res.data
				})
				.fail((err) => {
					console.log(err)
				})
			},
			handleClose() {
				this.editCampaignVisible = false;
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