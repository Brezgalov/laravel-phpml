$('#use-defaults').on('click', function(){
	var self = $(this);
	var defaultsEnabled = self.attr('data-default');
	var newText = self.attr('data-text');
	var selfText = self.text();

	self.text(newText);
	self.attr('data-text', selfText);

	if (defaultsEnabled == "1") {
		self.attr('data-default', "0");
		$('.form-unit.def').each(function(index, data) {
			var unit = $(data);
			unit.removeClass('disabled');	

			unit.children().each(function(index, data_child) {
				var child = $(data_child);
				child.prop('disabled', false);
			});
		});
	} else {
		self.attr('data-default', "1");
		$('.form-unit.def').each(function(index, data) {
			var unit = $(data);
			unit.addClass('disabled', true);	

			unit.children().each(function(index, data_child) {
				var child = $(data_child);
				child.prop('disabled', true);
			});
		});
	}
});

$('#draw-chart').on('click', function(){
	$.ajax({
		url: '/chart',
		method: 'post',
		dataType: 'json',
		data: {
			day: $('#day-select').val(),
			src: $('#src-select').val(),
			length: $('#length-select').val(),
			_token: $('input[name="_token"]').val()
		},
		success: function(data) { 
			console.log(data);
			var ctx = document.getElementById("chartJSContainer").getContext("2d");
			window.myScatter = new Chart(ctx, {
			    type: 'scatter',
			    data: {
			        datasets: [
				        {
				        	pointRadius: 1,
			        		pointBorderColor: 'red',
			        		borderColor: 'rgba(0,0,0,0)',
				            backgroundColor: 'rgba(0,0,0,0)',
				            label: '',
				            data: data.inputCoords
				        },
				        {
				        	pointRadius: 1,
			        		pointBorderColor: 'blue',
			        		borderColor: 'blue',
				            backgroundColor: 'rgba(0,0,0,0)',
				            label: '',
				            data: data.predictCoords
				        }
			        ]
			    },
			    options: {
			    	legend: {
			            display: false
			        },
			        scales: {
			        	xAxes: [{
			                scaleLabel: {
						    	display: true,
							    labelString: 'Кол-во минут прошедшее со времени 00:00'
						  	},
						  	ticks: {
		                        beginAtZero: true,
		                        min: 0,
		                        max: 1440
		                    }
			            }],
			            yAxes: [{
			                scaleLabel: {
						    	display: true,
							    labelString: 'Нагрузка'
						  	},
						  	ticks: {
		                        beginAtZero: true,
		                        min: 0
		                    }
			            }]
			        }
			    }
			});
		},
		error: function(data) {
			console.log(data);
		}
	});
});