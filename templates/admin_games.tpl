{strip}
{form}
	{jstabs}
		{jstab title="Games"}
				<p>
					{tr}Games with checkmarks are currently enabled, meaning they can be played, games without are disabled.  To enable or disable a game, check or uncheck it, and click the 'Change Preferences' button.{/tr}
				</p>
				{foreach key=name item=game from=$games}
						<div class="form-group">
							<div class="formlabel">
								<label for="game_{$game.title}">{biticon igame=$name iname="game_`$game.title`" iexplain="`$game.title`" iforce=icon}</label>
							</div>
							{forminput}
								<label>
									<input type="checkbox" value="{$game.type}" name="games[]" id="game_{$game.type}" {if $activeGames[$game.type] }checked="checked"{/if} />
									&nbsp; <strong>{$game.title|capitalize}</strong>
								</label>
								<div class="summary">
									{$game.summary}
								</div>
								<a href="{$smarty.const.KERNEL_PKG_URL}admin/index.php?page={$game.package}">Settings</a> | <a href="{$gameSystem->getPlayUrl($game.type)}">Play</a>
							{/forminput}
						</div>
					{if $game.installed}
					{/if}
				{/foreach}
		{/jstab}

		{jstab title="List Settings"}
			{legend legend="List Settings"}
				<input type="hidden" name="page" value="{$page}" />
				{foreach from=$formGamesLists key=item item=output}
					<div class="form-group">
						{formlabel label=$output.label for=$item}
						{forminput}
							{html_checkboxes name="$item" values="y" checked=$gBitSystem->getConfig($item) labels=false id=$item}
							{formhelp note=$output.note page=$output.page}
						{/forminput}
					</div>
				{/foreach}
			{/legend}
		{/jstab}

		<div class="form-group submit">
			<input type="submit" class="btn btn-default" name="games_settings" value="{tr}Change Preferences{/tr}" />
		</div>

	{/jstabs}
{/form}
{/strip}
