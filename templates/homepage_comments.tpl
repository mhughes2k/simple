			<div id="alertsSection">
				<div class="sectionTitle">Latest Comments</div>
				{foreach from=$latestComments key=v item=comment}
        <div class="alertitem">
					<div class="alertitem_header">
					
					</div>
					<div class="alertitem_alerttime">
          <A href=" 
					{if $comment.itemtype=='doc'}
  					{$config.home}index.php?option=office&cmd=viewdoc&id={$comment.itemid}
					{/if}
					">
          {$comment.subject}</a>
					</div>
					<div class="alertitem_body">
					{$comment.comment|truncate:10}
					</div>
				</div>
				{foreachelse}
				No Unread Comments.
				{/foreach}
			</div>
