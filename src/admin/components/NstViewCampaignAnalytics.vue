<template>
	<div class="nst_view_campaign_analytics">
		<div class="container">
			<pre>{{data}}</pre>
		</div>
	</div>
</template>


<script type="text/babel">
	export default {
		name: 'nst_view_campaign_analytics',
		props: ['campaign'],
		data() {
			return {
				campaign_id: parseInt(this.$route.params.id),
				data: {}
			}
		},

		methods: {
			fetchAnalyticsData() {
				jQuery.get(ajaxurl, {
					action: 'routes', 
					target_action: 'get-campaign-analytics-data',
					id: this.campaign_id
				})
	            .done((resonse) => {
	               this.data = resonse.data.analyticsData;
	            })
	            .fail((error) => {
	                console.log(error);
	            });
			}
		},

		mounted() {
			this.fetchAnalyticsData();
		}
	}
</script>