{strip}
	<a class="dropdown-toggle" data-toggle="dropdown" href="#"> {tr}{$packageMenuTitle}{/tr} <b class="caret"></b></a>
<ul class="{$packageMenuClass}">
		{if $gBitUser->hasPermission( 'p_games_view')}
			<li><a class="item" href="{$smarty.const.GAMES_PKG_URL}list.php">{tr}List Games{/tr}</a></li>
		{/if}
		{if $gBitUser->hasPermission( 'p_games_admin')}
			<li><a class="item" href="{$smarty.const.KERNEL_PKG_URL}admin/index.php?page=games">{tr}Admin{/tr}</a></li>
		{/if}
	</ul>
{/strip}
