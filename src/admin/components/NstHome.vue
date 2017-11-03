<template>
	<div>
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
				activeIndex: '1',
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
			handleClick() {

			},
		},
		mounted() {
			this.setNavIndexing();
		}
	}
</script>