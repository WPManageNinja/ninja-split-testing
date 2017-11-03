<template>
	<div>
		<h2>All Campaign</h2>
	</div>
</template>

<script type="text/babel">
	export default {
		name: 'NstAllCampaign',
		mounted() {
			this.componentEmit('navIndexing')
		}
	}
</script>