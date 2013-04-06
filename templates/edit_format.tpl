{strip}
{* We have to count these first because of the tikiwiki format options which may show even if it is the only format option. *}
{assign var=numformat value=0}
{foreach name=formatPlugins from=$gLibertySystem->mPlugins item=plugin key=guid}
	{if $plugin.is_active eq 'y' and $plugin.edit_field and $plugin.plugin_type eq 'format'}
		{assign var=numformat value=$numformat+1}
		{if $plugin.plugin_guid == "tikiwiki"}
			{assign var=format_options value=true}
		{/if}
	{/if}
{/foreach}
{if $numformat > 1 || $format_options}
	<div class="control-group">
		{formfeedback error=$errors.format}
		{formlabel label="Content Format"}
		{foreach name=formatPlugins from=$gLibertySystem->mPlugins item=plugin key=guid}
			{if $plugin.is_active eq 'y' and $plugin.edit_field and $plugin.plugin_type eq 'format'}
				{forminput}
					{if $numformat > 1}
						<label>
							<input type="radio" name="{$format_guid_variable|default:"format_guid"}" value="{$plugin.edit_field}"
							{if $game.format_guid eq $plugin.plugin_guid
								} checked="checked"{
							elseif !$game.format_guid and $plugin.plugin_guid eq $gBitSystem->getConfig('default_format', 'tikiwiki')
								} checked="checked"{
							/if
							} onclick="
								{if $gBitSystem->isPackageActive('quicktags')}
									{foreach from=$gLibertySystem->mPlugins item=tag key=guid}
										{if $tag.is_active eq 'y' and $tag.edit_field and $tag.plugin_type eq 'format'}
											{if $tag.plugin_guid eq $plugin.plugin_guid}
												showById
											{else}
												hideById
											{/if}
											('qt{$textarea_id}{$tag.plugin_guid}'); 
										{/if}
									{/foreach}
								{/if}
							"
						/> {$plugin.edit_label}
						</label>
					{/if}
					{if $plugin.plugin_guid == "tikiwiki"}
						{if $numformat > 1}		
							&nbsp;&nbsp;
						{/if}
					{/if}
					{formhelp note=$plugin.edit_help}
				{/forminput}
			{/if}
		{/foreach}
		{if $numformat > 1}
			{forminput}
				{formhelp note="Choose what kind of syntax you want to submit your data in."}
			{/forminput}
		{else}
			<input type="hidden" name="{$format_guid_variable|default:"format_guid"}" value="{$gBitSystem->getConfig('default_format','tikiwiki')}" />
		{/if}
	</div>
{else}
	<input type="hidden" name="{$format_guid_variable|default:"format_guid"}" value="{$gBitSystem->getConfig('default_format','tikiwiki')}" />
{/if}

{/strip}
