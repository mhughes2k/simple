{include file="SnoozeAlertDialog.tpl"}
{include file="DismissAlertDialog.tpl} 

			<div id="alertsSection">
				<div class="areaTitle">{$strings.MSG_ALERTS}</div>
        {if count($alerts)>0}
				<a href="index.php?option=office&cmd=dismissallalerts"{$strings.DISMISS_ALL_ALERT}</a>
				{/if}

				{foreach from=$alerts item=alert}
				<div class="alertitem">
					<div class="alertitem_header">
					<A href="index.php?
{if $alert->ItemType=='calendar'}option=office&cmd=viewcalendaritem{/if}
{if $alert->ItemType=='doc'}option=office&cmd=viewdoc{/if}&context=alert&id={$alert->ItemId}">{$alert->Title}</a>
					</div>
					<div class="alertitem_alerttime"> 
					{$alert->AlertTime}
					</div>
					<div class="alertitem_body">
					{$alert->Message}
					</div>
					<div class="alertitem_body">
						<a class="snoozeAlert" itemid="{$alert->id}" ><img src="{$config.alert_icon_snooze}" alt='{$strings.DISMISS_ALERT}'></a>
						<a class="snoozeAlert" itemid="{$alert->id}" >{$strings.SNOOZE_ALERT}</a> | 
						<a class="dismissAlert" itemid="{$alert->id}" ><img src="{$config.alert_icon_dismiss}" alt='{$strings.DISMISS_ALERT}'></a>
						<a class="dismissAlert" itemid="{$alert->id}">{$strings.DISMISS_ALERT}</a>
					</div>
				</div>
				{foreachelse}
				<div class="alertitem">
				{$strings.MSG_NO_ALERTS}
				</div>
				{/foreach}
			</div>
