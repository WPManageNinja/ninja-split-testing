<script>
import { Bar } from 'vue-chartjs'
export default{
	props: ['data'],
	extends: Bar,
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