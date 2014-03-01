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
					<label for="zipcode" class="col-sm-4 control-label">Your zipcode is </label>
					<div class="col-sm-8">
						<input type="text" name="zipcode" class="form-control" id="zipcode" placeholder="Zipcode" value="{{$user.zipcode}}" />
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
{{include file="footer.tpl"}}