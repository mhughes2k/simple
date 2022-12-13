Feedback for {$assessmentName}<br /><br />

Outcomes<br/>
<table>
{foreach from=$qrs item=qr}
<tr>
<td>{$qr.question}</td>
<td>{$qr.response}</td>

{/foreach}
</table>

Additional Comments:<br />
{$comments}