{literal}
<script type="text/javascript">
 $(document).ready(function () {
  $("$sendDialogForm").submit(function(evt) {
    alert('test');
    this.attr('disabled',true);
    evt.preventDefault();
  });
 }
 </script>
 {/literal}
<div id="sendDialog">
	<form action="index.php" method="post" id="sendDialogForm">
	<input type="hidden" name="option" value="office" />
	<input type="hidden" name="cmd" value="send" />
	<input type="hidden" name="documentid" value="{$documentid}" />
	<div>
	To:<input type="text" name="to" id="to" value="{$to}"/><br />
	From: {if $staff}<select name="sender" id="sender">
	                <option value="">-Select one-</option>
	                <option value="doc1">Jeff White</option>
	                <option value="doc2">Ralph Little</option>
	                <option value="">Agnes Brown</option>
	              </select>
			{else}
			[firmname]
			{/if}
	</div>
	
	<div><input type="submit" value="Send" id="sendDocumentButton"/></div>
	</form>
</div>