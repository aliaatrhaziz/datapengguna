// Call the dataTables jQuery plugin
$(document).ready(function() {
	window.dt = $('#dataTable').DataTable();
	setTimeout(() => {
		$('[data-toggle="tooltip"]').tooltip()
	}, 500);
});