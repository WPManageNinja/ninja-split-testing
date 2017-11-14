<template>
	<div class="nst_view_campaign_analytics">
		<el-row>
			<el-col class="center">
                <h2><i class="el-icon-document"></i> Analytics of this campaign</h2>
            </el-col>
		</el-row>
		<hr>
		<div class="container center">
			<el-row>
				<el-col :offset="2" :span="8" v-if="trafficSplitAmount.labels.length > 0">
					<PieChart  :data="trafficSplitAmount" :height="300"></PieChart>
					<label>Pages By Traffic</label>
				</el-col>
				<el-col :offset="2" :span="8" v-if="visitorCount.labels.length > 0">
					<PieChart  :data="visitorCount" :height="300"></PieChart>
					<label>Pages By Visitor</label>
				</el-col>
			</el-row>

			<el-row class="m-t-35">
				<el-col :offset="3" :span="12"  v-if="stat_by_date.labels.length > 0" class="center">
					<BarChart :data="stat_by_date" :height="300"></BarChart>
					<label>Visitor By Day</label>
				</el-col>
			</el-row>
			
		</div>
	</div>
</template>


<script type="text/babel">
	import BarChart from './NstBarChart.vue';
	import PieChart from './NstPieChart.vue';
	import {forEach, findIndex} from 'lodash';
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
				stat_by_date: {
						labels: [],
					    datasets: [
					        {
					          label: 'Visitors by day',
					          backgroundColor: '#20a0ff',
							  data: []
					        }
					    ]
				}
			}
		},

		methods: {
			fetchAnalyticsData() {
				jQuery.get(ajaxurl, {
					action: 'nst_routes', 
					target_action : 'get_analytics',
					campaign_id: this.campaign_id
				})
	            .done((resonse) => {
	               this.data = resonse.data;
	               forEach(this.data.visitors_by_pages, (item) => {
	               		let index = findIndex(this.data.pages, {id: item.campaign_url_id});
	               		this.trafficSplitAmount.labels.push(this.data.pages[index].title);
	               		this.visitorCount.labels.push(this.data.pages[index].title);
	               		this.trafficSplitAmount.datasets[0].data.push(this.data.pages[index].traffic_split_amount);
	               		this.visitorCount.datasets[0].data.push(item.records);
	               		this.trafficSplitAmount.datasets[0].backgroundColor.push('#'+Math.random().toString(16).substr(-6))
	               		this.visitorCount.datasets[0].backgroundColor.push('#'+Math.random().toString(16).substr(-6))
	               })
	               forEach(this.data.stat_by_day, (item) => {
	               		console.log(item.date)
	                	this.stat_by_date.labels.push(item.date);
	                	this.stat_by_date.datasets[0].data.push(item.records);
	                	// this.stat_by_date.datasets[0].backgroundColor.push('#'+Math.random().toString(16).substr(-6));
	               })
	            })
	            .fail((error) => {
	                console.log(error);
	            });
			}
		},

		mounted() {
			this.fetchAnalyticsData();
			this.$emit('setNavIndexing');
		}
	}
</script>