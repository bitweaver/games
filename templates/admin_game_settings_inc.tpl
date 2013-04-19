{strip}
<div class="control-group">
	{formfeedback warning=$errors.title}
	{formlabel label="Title" for="title"}
	{forminput}
	<input type="text" size="50" name="games[{$game.type}][title]" id="title" value="{$game.title|escape}" />
	{/forminput}
</div>

<div class="control-group">
	{formlabel label="Summary" for="summary"}
	{forminput}
		<input type="text" size="50" name="games[{$game.type}][summary]" id="summary" value="{$game.summary|escape}" />
		{formhelp note="Summary of the game."}
	{/forminput}
</div>

<div class="control-group">
	{include file="bitpackage:games/edit_format.tpl" game=$game format_guid_variable="games[`$game.type`][format_guid]"}
	{formlabel label="Description" for="description"}
	{forminput}
		{textarea id="description" name="games[`$game.type`][description]" noformat="y" edit=$game.description}
		{formhelp note="Description of the game."}
	{/forminput}
</div>

<div class="control-group">
	{formlabel label="Instructions" for="instructions"}
	{forminput}
	{textarea id="instructions" name="games[`$game.type`][instructions]" noformat="y" edit=$game.instructions}
		{formhelp note="Instructions and rules of the game."}
	{/forminput}
</div>
{/strip}
