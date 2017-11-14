<script>
import { Line } from 'vue-chartjs'
export default{
	props: ['data'],
	extends: Line,
	watch: {
		datasets() {
			this.createChart()
		}
	},
	methods: {
		createChart() {
		    this.renderChart({
		      ...this.data
		    })
		}
	},
	mounted() {
		this.createChart()
	}
}
</script>