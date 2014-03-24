{{include file="header.tpl" pageTitle="Settings"}}
	<div class="row marketing">
		<div class="col-lg-12">
			<h1>Settings for {{$user.username}}</h1>
{{foreach $errors as $error}}
			<div class="alert alert-{{if $error.type}}{{$error.type}}{{else}}danger{{/if}} alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<strong>{{$error.title}}</strong> - {{$error.body}}
			</div>
{{/foreach}}
			<form class="form-horizontal" role="form" method="POST">
				<div class="form-group">
					<label for="email" class="col-sm-4 control-label">Your email is </label>
					<div class="col-sm-8">
						<input type="email" name="email" class="form-control" id="email" placeholder="Email" value="{{$user.email}}" />
					</div>
				</div>
				<div class="form-group">
					<label for="phone" class="col-sm-4 control-label">Your phone is </label>
					<div class="col-sm-8">
						<input type="tel" name="phone" class="form-control" id="phone" placeholder="Phone" value="{{$user.phone}}" />
					</div>
				</div>
				<div class="form-group">
					<label for="zipcode" class="col-sm-4 control-label">Your zipcode is </label>
					<div class="col-sm-8">
						<input type="text" name="zipcode" class="form-control" id="zipcode" placeholder="Zipcode" value="{{$user.zipcode}}" />
					</div>
				</div>
				<div class="form-group">
					<label for="timezone" class="col-sm-4 control-label">Your timezone is </label>
					<div class="col-sm-8">
						<select class="form-control" name="timezone" id="timezone">
							<option value="America/New_York"{{if $user.timezone == "America/New_York"}} selected{{/if}}>Eastern</option>
							<option value="America/Chicago"{{if $user.timezone == "America/Chicago"}} selected{{/if}}>Central</option>
							<option value="America/Denver"{{if $user.timezone == "America/Denver"}} selected{{/if}}>Mountain</option>
							<option value="America/Phoenix"{{if $user.timezone == "America/Phoenix"}} selected{{/if}}>Mountain (no DST)</option>
							<option value="America/Los_Angeles"{{if $user.timezone == "America/Los_Angeles"}} selected{{/if}}>Pacific</option>
							<option value="America/Anchorage"{{if $user.timezone == "America/Anchorage"}} selected{{/if}}>Alaska (Anchorage)</option>
							<option value="America/Adak"{{if $user.timezone == "America/Adak"}} selected{{/if}}>Alaska (Adak)</option>
							<option value="Pacific/Honolulu"{{if $user.timezone == "Pacific/Honolulu"}} selected{{/if}}>Hawaii (no DST)</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="emailDaySendTime" class="col-sm-4 control-label">You wakeup around </label>
					<div class="col-sm-8">
						<input type="text" name="emailDaySendTime" class="form-control" id="emailDaySendTime" placeholder="8:00AM" value="{{$user.dayTime}}" />
					</div>
				</div>
				<div class="form-group">
					<label for="emailNightSendTime" class="col-sm-4 control-label">You leave work around </label>
					<div class="col-sm-8">
						<input type="text" name="emailNightSendTime" class="form-control" id="emailNightSendTime" placeholder="8:00AM" value="{{$user.nightTime}}" />
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-4 col-sm-8">
						<button type="submit" class="btn btn-default">Update</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	<script>
		window.onload = function()
		{
			$("#zipcode").blur(function()
			{
				if (/(^\d{5}$)|(^\d{5}-\d{4}$)/.test($("#zipcode").val()))
				{
					$.getJSON("https://maps.googleapis.com/maps/api/geocode/json?address=" + $("#zipcode").val() + "&sensor=false").done(function(data)
					{
						var lat = data.results[0].geometry.location.lat;
						var lng = data.results[0].geometry.location.lng;

						$.getJSON("https://maps.googleapis.com/maps/api/timezone/json?location=" + lat + "," + lng + "&sensor=false&timestamp=" + Math.round(new Date().getTime() / 1000)).done(function(data)
						{
							$("#timezone").val(data.timeZoneId);
						});
					});
				}
			});
		}
	</script>
{{include file="footer.tpl"}}