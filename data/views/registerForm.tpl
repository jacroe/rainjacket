{{include file="header.tpl" pageTitle="Register"}}
	<div class="row marketing">
		<div class="col-lg-12">
			<h1>Register</h1>
{{foreach $errors as $error}}
			<div class="alert alert-danger alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<strong>{{$error.title}}</strong> - {{$error.body}}
			</div>
{{/foreach}}
			<form class="form-horizontal" role="form" method="POST">
				<div class="form-group">
					<label for="username" class="col-sm-3 control-label">Username</label>
					<div class="col-sm-9">
						<input type="text" name="username" class="form-control" id="username" placeholder="Username" {{if $submitted.username}}value="{{$submitted.username}}"{{/if}} />
					</div>
				</div>
				<div class="form-group">
					<label for="email" class="col-sm-3 control-label">Email</label>
					<div class="col-sm-9">
						<input type="email" name="email" class="form-control" id="email" placeholder="Email" {{if $submitted.email}}value="{{$submitted.email}}"{{/if}} />
					</div>
				</div>
				<div class="form-group">
					<label for="password" class="col-sm-3 control-label">Password</label>
					<div class="col-sm-9">
						<input type="password" class="form-control" id="password" placeholder="Password" name="password" />
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-9">
						<button type="submit" class="btn btn-default">Register</button>
					</div>
				</div>
			</form>
		</div>
	</div>
{{include file="footer.tpl"}}