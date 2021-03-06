<div class="full-width-page">
	<section id="inputs">
		<form>
			{{csrf_field()}}
			<div class="form-row">
				<div class="form-unit">
					<label for="day-select">
						Выбор Дня
					</label>
					<select id="day-select">
						<option value="Sunday">Воскресенье</option>
						<option value="Monday">Понедельник</option>
						<option value="Tuesday">Вторник</option>
						<option value="Wednesday">Среда</option>
						<option value="Thursday">Четверг</option>
						<option value="Friday">Пятница</option>
						<option value="Saturday" selected >Суббота</option>
					</select>
				</div>
				<div class="form-unit">
					<label for="src-select">
						Данные по
					</label>
					<select id="src-select">
						<option value="sources" selected>Источникам</option>
						<option value="clients">Клиентам</option>
					</select>
				</div>
				<div class="form-unit">
					<label for="length-select">
						Отрезок регресси
					</label>
					<select id="length-select">
						<option value="5">5 мин</option>
						<option value="10" selected >10 мин</option>
						<option value="15">15 мин</option>
						<option value="30">30 мин</option>
						<option value="60">60 мин</option>
						<option value="0">24 часа</option>
					</select>
				</div>
				<div class="form-unit">
					<a id="draw-chart" href="/#chart">Построить модель</a>
				</div>
			</div>
		</form>
	</section>
	<section id="chart">
		<div class="chart-wrapper">
			<canvas id="chartJSContainer" width="2500" height="500"></canvas>
		</div>
	</section>
</div>

<link href="{!! asset('css/app.css') !!}" media="all" rel="stylesheet" type="text/css" />

<script src="{!! asset('js/jquery/jquery-3.2.1.min.js') !!}"></script>
<script src="{!! asset('js/chartJs/Chart.js') !!}"></script>
<script src="{!! asset('js/app.js') !!}"></script>