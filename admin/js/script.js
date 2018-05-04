$(document).ready(function(){




var csrf = $('meta[name=csrf-token]').attr("content"),
	url = '/admin/include/ajax.php';
				
		$('.remove-link').on('click', function(){
			var data_id = $(this).attr('data-id');

				result = confirm("Delete from link id "+data_id);


				if (result) {

					$.ajax({

			            type: 'POST',

			            url: url,

			            data: {delete_url : 'delete', id : data_id, csrf_token : csrf},

			            success: function(data) {
			            	var results = $.parseJSON(data);
			            	if (results.result == 'true') {
			            		location.reload();
			            	}else{
			            		alert(results.error);
			            	}


			            },

			            error:  function(xhr, str){

			                console.log(str + ' Возникла ошибка: ' + xhr.responseCode);

			            }

		        	});
				}
		})


		$('#short_add').on('submit', function(){

			var full_url = $('#full_url').val();

			$.ajax({

	            type: 'POST',

	            url: url,

	            data: {full_url : full_url, csrf_token : csrf},

	            success: function(data) {
	            	var results = $.parseJSON(data);
	            	if (results.result == 'true') {
	            		location.reload();
	            	}else{
	            		alert(results.error);
	            	}


	            },

	            error:  function(xhr, str){

	                console.log(str + ' Возникла ошибка: ' + xhr.responseCode);

	            }

        	});
		})


		/*
		*
		*
		* Chart script
		*
		*
		*/
		var densityCanvas = $("#countCharts");

		var hor_labels = [], hor_data = [];

		$('#horCharts > span.label').each(function(index){
		  hor_labels[index] = $( this ).text().toString();
		  hor_data[index] = parseInt($(this).attr('data-count'));
		});

		Chart.defaults.global.defaultFontSize = 18;
		console.log(hor_data);
		console.log(hor_labels);
		var densityData = {
		  label: 'Week Views',
		  data: hor_data,
		  backgroundColor: [
		    'rgba(0, 99, 132, 0.6)',
		    'rgba(30, 99, 132, 0.6)',
		    'rgba(60, 99, 132, 0.6)',
		    'rgba(90, 99, 132, 0.6)',
		    'rgba(120, 99, 132, 0.6)'
		  ],
		  borderColor: [
		    'rgba(0, 99, 132, 1)',
		    'rgba(30, 99, 132, 1)',
		    'rgba(60, 99, 132, 1)',
		    'rgba(90, 99, 132, 1)',
		    'rgba(120, 99, 132, 1)'
		  ],
		  borderWidth: 1,
		  hoverBorderWidth: 0
		};

		var chartOptions = {
		  scales: {
		    yAxes: [{
		      barPercentage: 0.5
		    }]
		  },
		  elements: {
		    rectangle: {
		      borderSkipped: 'left',
		    }
		  }
		};

		var barChart = new Chart(densityCanvas, {
		  type: 'horizontalBar',
		  data: {
		    labels: hor_labels,
		    datasets: [densityData],
		  },
		  options: chartOptions
		});





	var ver_labels = [], ver_data = [];

		$('#verCharts > span.label').each(function(index){
		  ver_labels[index] = $( this ).text().toString();
		  ver_data[index] = parseInt($(this).attr('data-count'));
		});


		var popCanvas = $("#weekCountCharts");
		var barChart = new Chart(popCanvas, {
		  type: 'bar',
		  data: {
		    labels: ver_labels,
		    datasets: [{
		      label: 'All time',
		      data: ver_data,
		      backgroundColor: [
		        'rgba(54, 162, 235, 0.6)',
		        'rgba(255, 206, 86, 0.6)',
		        'rgba(75, 192, 192, 0.6)',
		        'rgba(153, 102, 255, 0.6)',
		        'rgba(255, 159, 64, 0.6)'
		      ]
		    }]
		  }
		});


		var clipboard = new Clipboard('.hash-links');

		$('.hash-links').on('click', function(){
			var parent = $(this).parent().append('<span class="alert-success" id="copy-url">Copy url!</span>');
			setTimeout(function(){
				$('#copy-url').detach();
			}, 1000);
			
		})




		


});

