<div class="full-width-page">
	<section id="inputs">
		<form>
			{{csrf_field()}}
			<div class="form-unit">
				<label for="day-select">
					Выбор Дня
				</label>
				<select id="day-select">
					<option id="Sunday" selected>Воскресенье</option>
					<option id="Monday">Понедельник</option>
					<option id="Tuesday">Вторник</option>
					<option id="Wednesday">Среда</option>
					<option id="Thursday">Четверг</option>
					<option id="Friday">Пятница</option>
					<option id="Saturday">Суббота</option>
				</select>
			</div>
			<div class="form-unit">
				<label for="month-select">
					Данные по
				</label>
				<select id="month-select">
					<option id="sources" selected>Источникам</option>
					<option id="clients">Клиентам</option>
				</select>
			</div>
			<div class="form-unit">
				<label for="month-select">
					Файл для обучения
				</label>
				<input type="file" id="learning-file" style="display: none;" />
				<input type="button" value="Загрузить" onclick="$('#learning-file').click();" />
			</div>
			<a id="draw-chart" href="/#chart">Построить модель</a>
		</form>
	</section>
	<section id="chart">
		<canvas id="chartJSContainer" width="2500" height="500"></canvas>
	</section>
</div>

<style>
	.form-unit {
		max-width: 150px;
		width: 10%;
		display: inline-block;
	}
	.form-unit > * {
		display: block;
		width: 100%;
	}
	section#chart {
		overflow-x: hidden;
		margin-bottom: 100px;
		border: 2px solid black;
		border-radius: 5px;
		padding: 10px;
	}
</style>

<!--<link href="{!! asset('css/all.css') !!}" media="all" rel="stylesheet" type="text/css" />-->
<script src="{!! asset('js/jquery/jquery-3.2.1.min.js') !!}"></script>
<script src="{!! asset('js/chartJs/Chart.js') !!}"></script>
<script>
	$('#draw-chart').on('click', function(){
		$.ajax({
			url: '/chart',
			method: 'post',
			dataType: 'json',
			data: {
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
			                        max: 1440
			                    }
				            }],
				            yAxes: [{
				                scaleLabel: {
							    	display: true,
								    labelString: 'Нагрузка'
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
</script>