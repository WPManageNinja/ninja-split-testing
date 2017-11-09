<template>
	<div>
		<div class="right">
			<el-button type="primary" @click="dialogVisible = true" icon="plus"> Add New Campaign</el-button>
		</div>

		<div>
			<el-dialog
			  title="Add New Campaign"
			  :visible.sync="dialogVisible"
			  width="30%"
			  :before-close="handleClose">
			  	<el-form :model="form">
				    <el-form-item label="Title">
				      <el-input v-model="form.title" auto-complete="off"></el-input>
				    </el-form-item>
				  	<nst_url_search v-model="selected_url" :title="'Select Your Target URL'"></nst_url_search>
			  </el-form>
			  <span slot="footer" class="dialog-footer">
			    <el-button @click="dialogVisible = false">Cancel</el-button>
			    <el-button type="primary" @click="submitNewCampaign">Continue <i class="el-icon-d-arrow-right el-icon-right"></i></el-button>
			  </span>
			</el-dialog>
		</div>
		

		<el-menu :default-active="activeIndex" class="el-menu-demo" mode="horizontal">
		  <router-link :to="{name: 'nst_home'}"> 
		  	<el-menu-item index="1">Ninja Split Testing</el-menu-item>
		  </router-link>
		  <router-link :to="{name: 'nst_settings'}"> 
		  	<el-menu-item index="2">Settings</el-menu-item>
		  </router-link>
		  <router-link :to="{name: 'nst_help'}"> 
		  	<el-menu-item index="3">Help</el-menu-item>
		  </router-link>
		</el-menu>

		<router-view @navIndexing="setNavIndexing"></router-view>
		
	</div>
</template>

<script type="text/babel">
	import NstUrlSearch from './NstUrlSearch.vue';
	export default {
		name: 'NstHome',
		components: {
		    'nst_url_search' : NstUrlSearch
		},
		data(){
			return {
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
				dialogVisible: false,
				targetDialogVisible: false,
				activeIndex: '1',
				form: {},
				navIndex: {
					nst_home: '1',
					nst_settings: '2',
					nst_help: '3'
				},
			}
		},
		watch: {
			['$route.query']() {
				this.checkAddCampaignPresentOrNot();
			}
		},
		methods: {
			setNavIndexing() {
				this.activeIndex = this.navIndex[this.$route.name];
			},

			handleClose() {
				this.dialogVisible = false;
			},

			onEditorChange({text}) {
				this.form.description = text;
			},
            submitNewCampaign() {
			    
				jQuery.post(ajaxurl, {
					action: 'routes', 
					target_action: 'add-campaign', 
					title : this.form.title, 
					post_id: this.selected_url.post_id,
					permalink: this.selected_url.permalink
				})
                    .done((response) => {
                        this.$message({
                            showClose: true,
                            message: response.data.message,
                            type: 'success'
                        });
                        this.$router.push({
							name: 'nst_view_testing_page',
							params: {
								id: response.data.id
							}
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

			handleClick() {

			},
			checkAddCampaignPresentOrNot() {
				if(this.$route.query.hasOwnProperty('campaign')){
					this.$route.query.campaign ? this.visibleAddCampaignDialog() : this.handleClose();
				} else {
					this.handleClose();
				}
			},
			visibleAddCampaignDialog(){
				this.dialogVisible = true;
			}
		},
		mounted() {
			this.setNavIndexing();
			this.checkAddCampaignPresentOrNot();
		}
	}
</script>

<style lang="scss">

	.right {
		text-align: right;
		margin-top: 10px;
		padding-right: 10px;
	}
</style>