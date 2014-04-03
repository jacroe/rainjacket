<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title></title>
		<style>
			body {background-color: #ccc; font-size: 20px; font-family: Arial, sans-serif;}
			a, a:hover {color: #000; text-decoration: none; border-bottom: 1px dotted #000;}
			table.rj-temperature tr {text-align: center}
			table.rj-temperature tr.temps {font-size: 2em;}
			table.rj-temperature td {border-left: 1px solid #ccc;}
			table.rj-temperature td:first-child {border-left: none}
			table.rj-temperature td img {display: inline}
		</style>
	</head>
	<body>
		<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
			<tr>
				<td align="center" valign="top">
					<table border="0" cellpadding="20" cellspacing="0" width="600px" id="emailContainer">
						<tr>
							<td align="center" valign="top">
								<table border="0" cellspacing="0" width="100%" id="emailHeader">
									<tr>
										<td align="center" valign="top">
											<img src="http://therainjacket.com/inc/images/logo.png" alt="Rainjacket"width="40%" />
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td align="center" valign="top">
								<table border="0" cellspacing="0" width="100%" id="emailBody">
									<tr>
										<td align="center" valign="top">
											<p><strong>Your {{if $isDay}}morning{{else}}evening{{/if}} forecast for {{$city}}, {{$state}}</strong></p>
											<p>{{$forecast}}</p>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td align="center" valign="top">
								<table class="table rj-temperature">
									<tbody>
										<tr>
					{{for $i=0 to ($lookingAhead|@count - 1)}}
											<td><img src="http://therainjacket.com/inc/images/weather/{{$lookingAhead[$i].image}}.png" class="img-responsive" alt="{{$lookingAhead[$i].condition}}" title="{{$lookingAhead[$i].condition}}" /></td>
					{{/for}}
										</tr>
										<tr class="temps">
					{{for $i=0 to ($lookingAhead|@count - 1)}}
											<td>{{$lookingAhead[$i].temp}}F</td>
					{{/for}}
										</tr>
										<tr>
					{{for $i=0 to ($lookingAhead|@count - 1)}}
											<td>{{$lookingAhead[$i].time|upper}}</td>
					{{/for}}
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
						<tr>
							<td align="center" valign="top">
								<table border="0" cellspacing="0" width="100%" id="emailFooter">
									<tr>
										<td align="center" valign="top">
											<a href="http://therainjacket.com/forecast.php?user={{$user}}">View online</a> | <a href="http://therainjacket.com/settings.php">Preferences</a> | <a href="http://therainjacket.com/about.php">About</a>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>