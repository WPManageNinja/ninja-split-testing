<script>
import { Line } from 'vue-chartjs'
export default{
	props: ['datasets', 'labels'],
	extends: Line,
	watch: {
		datasets() {
			this.createChart()
		}
	},
	methods: {
		createChart() {
		    this.renderChart({
		      labels: this.labels,
		      datasets: this.datasets
		    })
		}
	},
	mounted() {
		this.createChart()
	}
}
</script>