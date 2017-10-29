	<h3 class="center"><?php echo T('添加课程分类'); ?></h3>

	<fieldset>
		<legend><?php echo T('添加课程'); ?></legend>
		<div class="row">
			<form id="add_Class" method="post" onsubmit="return false;" class="col s12">
				<input name="service" value="Class.create_Class" type="hidden">
				<input name="action" value="post" type="hidden">
				<div class="col s12">
					<div class="input-field">
						<i class="material-icons prefix">label_outline</i>
						<input name="name" type="text" class="validate">
						<label for="name"><?php echo T('课程名'); ?></label>
					</div>
				</div>

				<div class="col s12">
					<div class="input-field">
						<i class="material-icons prefix">label_outline</i>
						<input name="tips" type="text" class="validate">
						<label for="tips"><?php echo T('课程说明'); ?></label>
					</div>
				</div>
				<div class="col s12 center">
					<button type="submit" name="submit"
							class="btn waves-effect waves-light"><?php echo T('添加课程'); ?></button>
				</div>
			</form>
	</fieldset>