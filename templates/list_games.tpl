{* $Header$ *}
{strip}
<div class="floaticon">{bithelp}</div>

<div class="listing games">
	<div class="header">
		<h1>{tr}Games{/tr}</h1>
	</div>

	<div class="body">
		{minifind sort_mode=$sort_mode}


			<table class="table data">
				<tr>
					<th>{smartlink ititle="Title" isort=title offset=$control.offset}</th>

					{if $gBitSystem->isFeatureActive( 'games_list_summary' ) eq 'y'}
						<th>{smartlink ititle="Summary" isort=summary offset=$control.offset}</th>
					{/if}

					{if $gBitSystem->isFeatureActive( 'games_list_description' ) eq 'y'}
						<th>{smartlink ititle="Description" isort=description offset=$control.offset}</th>
					{/if}
				</tr>

				{foreach item=games from=$gamesList}
					<tr class="{cycle values="even,odd"}">
						<td><a href="{$smarty.const.GAMES_PKG_URL}index.php?game={$games.type|escape:"url"}" title="{$games.game_id}">{$games.title|escape}</a></td>

						{if $gBitSystem->isFeatureActive( 'games_list_summary' )}
							<td>{$games.summary|escape}</td>
						{/if}

						{if $gBitSystem->isFeatureActive( 'games_list_description' )}
							<td>{$games.description|escape}</td>
						{/if}
					</tr>
				{foreachelse}
					<tr class="norecords"><td colspan="16">
						{tr}No records found{/tr}
					</td></tr>
				{/foreach}
			</table>

		{pagination}
	</div><!-- end .body -->
</div><!-- end .admin -->
{/strip}
