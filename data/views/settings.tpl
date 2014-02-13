{{include file="header.tpl" pageTitle="Settings"}}
	<div class="row marketing">
		<div class="col-lg-12">
			<h1>Settings for {{$user.username}}</h1>
{{foreach $errors as $error}}
			<div class="alert alert-danger alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<strong>{{$error.title}}</strong> - {{$error.body}}
			</div>
{{/foreach}}
			<form class="form-horizontal" role="form" method="POST">
				<div class="form-group">
					<label for="email" class="col-sm-3 control-label">Your email is </label>
					<div class="col-sm-9">
						<input type="email" class="form-control" id="email" placeholder="Email" value="{{$user.email}}" readonly />
					</div>
				</div>
				<div class="form-group">
					<label for="zipcode" class="col-sm-3 control-label">Your zipcode is </label>
					<div class="col-sm-9">
						<input type="text" name="zipcode" class="form-control" id="zipcode" placeholder="Zipcode" value="{{$user.zipcode}}" readonly/>
					</div>
				</div>
				<div class="form-group">
					<label for="emailSend" class="col-sm-3 control-label">You wakeup at </label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="emailSend" placeholder="8:00AM" name="emailSend" value="{{$user.time}}" readonly />
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-9">
						<button type="submit" class="btn btn-default">Update</button>
					</div>
				</div>
			</form>
		</div>
	</div>
{{include file="footer.tpl"}}