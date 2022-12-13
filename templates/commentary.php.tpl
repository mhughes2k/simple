<div id="commentary">
	<div class="sectionBox" >
		<div class="sectionTitle">
		Comments <a class="toggleButton" href="javascript:toggleElement('ui_commentform');">Show/Hide</a>
		</div>
		<div  id="ui_commentform" style="display:block">
			{if $commentary_disabled_message!=''}
				{$commentary_disabled_message}
			{else}
				<div class="commentForm">
				{if !$projectInActive}
					<form name="addcommentform" method="post" action="index.php">
						<input type="hidden" name="option" value="office"/>
						<input type="hidden" name="cmd" value="addcomment"/>
						<input type="hidden" name="id" value="{$comment_id}"/>
						<input type="hidden" name="itemtype" value="{$comment_itemtype}"/>
						<input type="hidden" name="redir" value="{$comment_redir}" />
						Subject:<input type="text" name="comment_subject"><br/>
						{if $canMaskerade}
							Add As: 
							<select name="commenter" id="commenter">
								<option value="{$user->displayName}">{$user->displayName} (Yourself)</option>
								{foreach from=$commentary_projectRoles key=key item=role}
									<option value="{$role->name}">{$role->name} ({$role->projectrole})</option>
								{/foreach}
							</select>
							<br>
						{/if}
						Body:<textarea name="comment_comment"></textarea><br/>
						<input type="submit" value="Add" />
					</form>
				{/if}
				</div>
			<!--	<a href="#" class="requirement">?<span>Can these comments be made collapsible?</span></a>-->
				<div id="comments" class="commentList_outer">
					{foreach from=$commentary item=comment}
						<div class="comment">
							<div class="commentHeader">
								<div style="float:right">
								Delete
								<a
								target="top" 
								href="http://technologies.law.strath.ac.uk/TLE2/PlatformDocumentation/FunctionalSpecification/ar01s04.html#req-9">?</a>
								</div>
								<div><strong>By:</strong> {if $comment->displayname==''}{$comment->GetAuthorName()}{else}{$comment->displayname}{/if} ({$comment->userid})</div>
								<div><strong>On</strong> {$comment->commentcreated}</div>
								<div><strong>Subject:</strong> {$comment->subject}</div>
								<div><strong>Body</strong></div>			
							</div>
							<div class="commentBody">
								{$comment->comment}
							</div>
						</div>
					{foreachelse}
						No Comments.
					{/foreach}
				</div>
			{/if}
		</div>
	</div>
	
	{if $admincommentary_enabled}
		<div class="sectionBox">
			<div class="sectionTitle">Administrative Comments <a class="toggleButton" href="javascript:toggleElement('ui_admincomments');">Show/Hide</a></div>
			{if $admincommentary_disabled_message!=''}
				{$admincommentary_disabled_message}
			{else}
				<div id="ui_admincomments" style="display:none">
					<div class="admincommentForm">
						<form name="addcommentform" method="post" action="index.php">
							<input type="hidden" name="option" value="office"/>
							<input type="hidden" name="cmd" value="addcomment"/>
							<input type="hidden" name="id" value="{$admincommentary_id}"/>
							<input type="hidden" name="itemtype" value="{$admincommentary_itemtype}"/>
							<input type="hidden" name="redir" value="{$admincommentary_redir}" />
							<input type="hidden" name="admincomment" value="1"/>
							Subject:<input type="text" name="comment_subject"><br/>
							Body:<textarea name="comment_comment"></textarea><br/>
							<input type="submit" value="Add" />
						</form>
					</div>
					<div id="admincomments" class="commentList_outer">
						{foreach from=$admincommentary_comments item=comment}
							<div class="comment">
								<div class="commentHeader">
									<div style="float:right">
									Delete
									<a
									target="top" 
									href="http://technologies.law.strath.ac.uk/TLE2/PlatformDocumentation/FunctionalSpecification/ar01s04.html#req-9">?</a>
									</div>
									<div><strong>Subject:</strong> {$comment->subject}</div>
									<div><strong>By:</strong> {$comment->GetAuthorName()} ({$comment->userid})</div>
									<div><strong>On</strong> {$comment->commentcreated}</div>
									<div><strong>Body</strong></div>			
								</div>
								<div class="commentBody">
									{$comment->comment}
								</div>
							</div>
						{foreachelse}
							No Comments.
						{/foreach}
					</div>
				{/if}
			</div><!--end UI-->
		</div>
	{/if}
</DIV>
