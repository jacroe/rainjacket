<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Rainjacket</title>
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="{{$ROOT_PATH}}inc/css/jumbotron-narrow.css" />
	<link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="{{$ROOT_PATH}}inc/css/styles.css" />

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
<div class="container">
	<div class="jumbotron">
		<h1 class="rj-logo">Rainjacket</h1>
		<p class="lead">{{$data.forecast}}</p>
	</div>
	<div class="row marketing">
		<div class="col-lg-12">
			<!--<div class="alert alert-success alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<strong>Some title</strong> - Some text
			</div>-->
			<table class="table rj-table rj-temperature">
				<tbody>
					<tr>
{{for $i=0 to ($data.lookingAhead|@count - 1)}}
						<td><img src="{{$ROOT_PATH}}inc/images/weather/{{$data.lookingAhead[$i].image}}.png" class="img-responsive" alt="{{$data.lookingAhead[$i].condition}}" title="{{$data.lookingAhead[$i].condition}}" /></td>
{{/for}}
					</tr>
					<tr class="temps">
{{for $i=0 to ($data.lookingAhead|@count - 1)}}
						<td>{{$data.lookingAhead[$i].temp}}F</td>
{{/for}}
					</tr>
					<tr>
{{for $i=0 to ($data.lookingAhead|@count - 1)}}
						<td>{{$data.lookingAhead[$i].time|upper}}</td>
{{/for}}
					</tr>
				</tbody>
			</table>
{{if $data.pollen}}
			<h1>Pollen levels</h1>
			<table class="table rj-table rj-pollen">
				<tbody>
					<tr class="levels">
{{for $i=0 to ($data.pollen|@count -2)}}
						<td><span class=pollen-{{$data.pollen[$i].word}}>{{$data.pollen[$i].word|upper}}</span></td>
{{/for}}
					</tr>
					<tr>
					<td>TODAY</td>
					<td>TOM.</td>
					<td>NEXT DAY</td>
				</tbody>
			</table>
{{/if}}
			<h1>Wind speed</h1>
			<p class="windSpeed"><span>{{$data.wind}}</span></p>
		</div>
	</div>
{{include file="footer.tpl"}}