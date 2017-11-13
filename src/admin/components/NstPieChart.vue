<script>
import { Pie } from 'vue-chartjs'
export default{
	props: ['data'],
	extends: Pie,
	watch: {
		data() {
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
		console.log(this.datasets)
	}
}
</script>