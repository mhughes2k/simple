<form action="index.php" method="post">
<input type="hidden" name="option" value="{$editVariablesOption}"/>
<input type="hidden" name="cmd" value="{$editVariablesCmd}"/>
<input type="hidden" name="id" value="{$id}">
Counter Text: <input type="text" name="counterName" value="{$counterName|default:""}"> <br />
Counter: <input type="text" name="counter" value="{$counter|default:""}"> <br />
Counter Step: <input type="text" name="counterStep" value="{$counterStep|default:"1"}"><br />
Skip simulationCreated Event after create? <input type="checkbox" name="skipSimCreatedEvent" value="true" {if $skipSimCreatedEvent}checked{/if}/> <br/>
<table border="1" width="98%">
    <tr>
    <td colspan="2"><input type="submit" value="{$editVariablesOkButtonLabel}">&nbsp;<input type="submit" name="createAnother" value="Create and Create Another"></td>
    </tr>
{foreach from=$variableslist key=variableName item=value}
  {if $variableName !='' & $variableName != ' '}
    <tr>
    <td>
    {literal}{{/literal}{$variableName}{literal}}{/literal}
    </td>
    <td>
    <input type="text" name="var_{$variableName}" value="{$value|escape}" style="width:97%"/>
    </td>
    </tr>
   {/if}
{/foreach}   
    <tr>
    <td colspan="2"><input type="submit" value="{$editVariablesOkButtonLabel}">&nbsp;<input type="submit" name="createAnother" value="Create and Create Another"></td>
    </tr>

</table>
</form>
