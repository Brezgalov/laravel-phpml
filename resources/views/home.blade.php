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
				<a id="use-defaults" data-default="1" data-text="Данные по умолчанию">Свои данные</a>
			</div>
			<div class="form-unit def disabled">
				<label for="month-select">
					Файл для обучения
				</label>
				<input type="file" id="learning-file" style="display: none;" disabled />
				<input type="button" value="Загрузить" onclick="$('#learning-file').click();" disabled />
			</div>
			<div class="form-unit def disabled">
				<label for="testing-file">
					Файл для тестирования
				</label>
				<input type="file" id="testing-file" style="display: none;" disabled />
				<input type="button" value="Загрузить" onclick="$('#testing-file').click();" disabled />
			</div>
			<div class="form-unit">
				<a id="draw-chart" href="/#chart">Построить модель</a>
			</div>
		</form>
	</section>
	<section id="chart">
		<canvas id="chartJSContainer" width="2500" height="500"></canvas>
	</section>
</div>

<link href="{!! asset('css/app.css') !!}" media="all" rel="stylesheet" type="text/css" />

<script src="{!! asset('js/jquery/jquery-3.2.1.min.js') !!}"></script>
<script src="{!! asset('js/chartJs/Chart.js') !!}"></script>
<script src="{!! asset('js/app.js') !!}"></script>