<template>
	<div>
		<div class="right">
			<el-button type="primary" @click="dialogVisible = true">Add New Campaign</el-button>
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

				<label>Description</label>	
			    <quill-editor class="m-t-10" :content="form.description"
                      :options="editorOption"
                      @change="onEditorChange($event)">
                </quill-editor>
			    
			  </el-form>
			  <span slot="footer" class="dialog-footer">
			    <el-button @click="dialogVisible = false">Cancel</el-button>
			    <el-button type="primary" icon="el-icon-arrow-right" @click="TargetUrl">Continue</el-button>
			  </span>
			</el-dialog>
		</div>
		

		<el-menu :default-active="activeIndex" class="el-menu-demo" mode="horizontal">
		  <router-link :to="{name: 'nst_home'}"> 
		  	<el-menu-item index="1">Ninja Split Testing</el-menu-item>
		  </router-link>
		  <router-link :to="{name: 'nst_add_campaign'}"> 
		  	<el-menu-item index="2">Add Campaign</el-menu-item>
		  </router-link>
		  <router-link :to="{name: 'nst_settings'}"> 
		  	<el-menu-item index="3">Settings</el-menu-item>
		  </router-link>
		  <router-link :to="{name: 'nst_help'}"> 
		  	<el-menu-item index="4">Help</el-menu-item>
		  </router-link>
		</el-menu>

		<router-view @navIndexing="setNavIndexing"></router-view>
	</div>
</template>

<script type="text/babel">
	export default {
		name: 'NstHome',
		data(){
			return {
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
					nst_add_campaign: '2',
					nst_settings: '3',
					nst_help: '4'
				},
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
			TargetUrl() {
				var self = this;

				jQuery.post(ajaxurl, {
					action: 'routes', 
					target_action: 'add-campaign', 
					title : self.form.title, 
					description : self.form.description
				})
                    .done((res) => {
                        self.$router.push({
							name: 'nst_view_settings_page',
							params: {
								id: res.data.id
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
		},
		mounted() {
			this.setNavIndexing();

			// 

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