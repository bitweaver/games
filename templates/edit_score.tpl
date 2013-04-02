<h1>{tr}Save Your Score{/tr}</h1>
{if $gBitUser->isRegistered()}
	<h3>{tr}Welcome Back{/tr}, {$gBitUser->getDisplayName()}</h3>
{/if}
<div><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis vitae massa sapien, id porttitor quam. Pellentesque ultricies enim nec felis placerat dictum. Nam nibh eros, elementum eget auctor sit amet, aliquam a odio.</p></div>
{form action="javascript:;" enctype="multipart/form-data" id="edit_score"}
	{if !$gBitUser->isRegistered()}
		<div class="control-group">
			{formlabel label="Real name" for="real_name"}
			{forminput}
				<input type="text" size="30" name="real_name" id="real_name" value="{$smarty.request.real_name}" />
			{/forminput}
		</div>
		<div class="control-group">
			{formfeedback error=$errors.email}
			{formlabel label="Email" for="email"}
			{forminput}
				<input type="text" size="30" name="email" id="email" value="{$reg.email}" />
			{/forminput}
		</div>
	{/if}
	<div class="control-group submit">
		<input type="button" name="cancel_save_score" value="{tr}Cancel{/tr}" />
		<input type="button" name="save_score" value="{tr}Save{/tr}" />
	</div>
{/form}
