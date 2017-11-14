<template>
	<div class="nst_view_campaign_analytics">
		<div class="m-l-20 m-t-10">
		</div>
		<div class="container center">
			<el-row>
				<el-col :span="9" v-if="trafficSplitAmount.labels.length > 0">

					<PieChart  :data="trafficSplitAmount"></PieChart>
					<label>Traffic Split Amount</label>
				</el-col>
				<el-col :span="9" v-if="trafficSplitAmount.labels.length > 0">
					<PieChart  :data="visitorCount"></PieChart>
					<label>Counting Visitor</label>
				</el-col>
			</el-row>
			
		</div>
	</div>
</template>


<script type="text/babel">
	import BarChart from './NstBarChart.vue';
	import PieChart from './NstPieChart.vue';
	import {forEach} from 'lodash';
	export default {
		name: 'nst_view_campaign_analytics',
		props: ['campaign'],
		components: {
			BarChart,
			PieChart
		},
		data() {
			return {
				campaign_id: parseInt(this.$route.params.id),
				data: {},
				trafficSplitAmount: {
					labels: [],
					datasets: [
						{
							labels: 'Traffic Split amount',
							backgroundColor: [],
							data: []
						}
					]
				},
				visitorCount: {
					labels: [],
					datasets: [
						{
							labels: 'Vistors',
							backgroundColor: [],
							data: []
						}
					]
				},
				pageViewStatus: {
						labels: [],
					    datasets: [
					        {
					          label: 'GitHub Commits',
					          backgroundColor: '#f87979',
					          data: [40, 20, 12, 39, 10, 40, 39, 80, 40, 20, 12, 11]
					        }
					    ]
				}
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
	               forEach(this.data.activePageData, (item) => {
	               		this.trafficSplitAmount.labels.push(item.target_url);
	               		this.visitorCount.labels.push(item.target_url);
	               		this.trafficSplitAmount.datasets[0].data.push(item.traffic_split_amount);
	               		this.visitorCount.datasets[0].data.push(item.visit_counts);
	               		this.trafficSplitAmount.datasets[0].backgroundColor.push('#'+Math.random().toString(16).substr(-6))
	               		this.visitorCount.datasets[0].backgroundColor.push('#'+Math.random().toString(16).substr(-6))
	               })
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