<html>
<head>
<script src="themes/{$config.THEME}/jquery-1.4.4.min.js" type="text/javascript"></script>
<style>
{literal}
body {
	margin:5px;
	font-family:Helvetica, Sans-Serif
}
h1 {
	font-size:1.3em;
}
#page {
	width:100%;
	height:550px;
}
#assessmentToolbar {
	position:relative;
	left:0px;
	right:0px;
	padding:10px;
	/*border:1px solid grey;*/
	;
}
#assessedDocument {
 width:70%;
/* height:550px;*/
 float:left;
/* border:1px solid black;*/
 overflow:clip;
}
#assessedDocumentContent {
	position:relative;
	width:70%;
	height:70%;
}
#assessedDocumentContentFrame {
	position:relative;
	width:100%;
	height:100%;
}
#assessedDocumentMeta {
	width:70%;
	height:20%;
	margin-bottom:30px;
	border:solid 1px black;
	border-right:solid 2px black;
	border-bottom:solid 2px black;
	overflow:hidden;
}
#assessmentForm {
	width:29%;
	padding:2px;
	background-color:palegoldenrod;
	float:right;

	border:1px solid black;
	
}	
.assessmentItem{
	margin-top:10px;
}
.assessmentOption{
float:right;
}
#btnSubmitFeedback{
	float:right;
}
.right{
	float:right;
}
#feedbackContainer {
    border:solid 1px black;
    background-color:palegoldenrod;
    position:absolute;
    height:500px;
    width:500px;
    top:10px;
    left:10px;
    padding:10px;
    overflow:scroll;
}
#feedbacktext {
    background-color:white;
    border: inset 2px white;
}
{/literal}
</style>
<script>
{literal}


function chooseAssessment() {
	var assessmentid = $("#select_Assessments").val();
	var url ='index.php?option=FeedBack&cmd=getassesmentformdata&assessmentid='+assessmentid;
	$.getJSON('index.php?option=FeedBack&cmd=getassessmentformdata&assessmentid='+assessmentid, constructFeedbackForm);
    $("#selectedAssessmentID").val(assessmentid);
	////alert(url)
	//return;

}
function constructFeedbackForm(data) {
	//alert('constructing form');
	//alert(data.questions);
	//item.name.appendTo("#af_selected");
	//alert(data);
	//$("af_selected").append(data);
	//return;
//alert(data);
    if (data == "false") {
        return;
    }

    //$("#assessmentToolbar").slideUp('fast');
    
       $("#af_unselected").fadeOut("fast", function() {
 	    //$("#af_selected").clear();
 	    $("#af_selected").fadeIn('fast');
    });
    $("#fb_name").html("<h2>"+data.name+"</h2>");
    $("#fb_sender").val(data.sender);
    $("#fb_questions").html('');
	$.each(data.questions, function(i,item)
	 {
		//alert(i);
	 	$("<p>").attr('id','q_'+i).text((i+1)+'. '+item.text).appendTo("#fb_questions");
	 	var a =$("<select/>").attr('name','q_'+i).addClass('right').appendTo("#fb_questions");
	 	$.each(item.answers, function(i,answer)
	 	{
	 		$("<option/>").attr('value',answer).attr('text',answer).appendTo(a);
	 	})
	 	$("<br/>").appendTo("#fb_questions");
	 }
	);
}
function ShowFeedback(evt) {
    evt.preventDefault();
    $("#feedbacktext").html('');
    $.each($("#fb_questions :input"), 
        function(i) {
            $("#feedbacktext").html($("#feedbacktext").html() +"<p>"+$("p#"+$(this).attr('name')).html() +'</p><p>'+ $(this).val()+"</p>");
        }
    );
    $("#feedbacktext").html("<h2>Feedback</h2>"+$("#feedbacktext").html()+"<hr><h2>Additional Comments</h2><p>"+$("#additionalComments").val()+"</p>");
    $("#btnSendFeedback").show();
    $("#feedbackContainer").fadeIn('fast');
}
function SubmitFeedback(evt)
{
    var submitUrl = 'index.php?option=FeedBack&cmd=submitformdata&projectid='+$("#projectid").val()+'&content='+escape($("#feedbacktext").html())+"&sender="+$("#fb_sender").val();
    alert(submitUrl);
return;
    $.getJSON(submitUrl,
        function(data) {
            if (data ==true) {
                //we're all done and should probably close!
                if(confirm('Close Window?')) {
                    window.close();
                }
            }
        }    
    );
}
function GetAssessments() {	
	$.getJSON('index.php?option=FeedBack&cmd=getprojectassessments',
		function(data) {
			$.each(data, function(i,item){
            	//$("<img/>").attr("src", item.appendTo("#images");	
            	$("<option/>").attr('value',item.doctemplateuid).attr('text',item.visiblename).appendTo("#select_Assessments");
            	//alert(i);
          })
   });
}

$(document).ready (function (){
    $("#feedbackContainer").hide();//.fadeIn('fast');
    $("#btnSendFeedback").hide();
	$("#btnSelectAssessmentForm").click( function() {
		//alert('clicked');
		chooseAssessment();
	});
	/*
	$("#btnSubmitFeedback").click(function(evt) {
		evt.preventDefault();
		alert('Submitting Feedback');
	});]
	*/
    $("#feedbackContainer_close").click(function(evt) {
        $("#feedbackContainer").hide();
        }
    );
	$("#btn_preview").click(ShowFeedback);
    $("#btnSendFeedback").click(SubmitFeedback);
	GetAssessments();
});
{/literal}
</script>
</head>
<body>
<div id="page"><div id="assessmentForm">
<h1>Assessment Forms</h1>
<p>You can only use <strong>one</strong> form, you cannot mix and match arbitrary forms.</p>
<div id="assessmentToolbar">

Please select: <select id="select_Assessments">
</select>
<button id="btnSelectAssessmentForm">Select</button>
</div>

<div id="af_unselected">
</div>
<!--<form id="af_selected" style="display:none;" action="index.php" method="POST"></form>//-->
<div id="af_selected">
<input type='hidden' name='option' value='feedback'/>
<input type='hidden' name='cmd' value='submitformdata'/>
<input type='hidden' name='assessmentId' value='' id="selectedAssessmentID"/>
<input type='hidden' name='projectid' value='{$projectid}' id="projectid"/>
<input type='hidden' name='fb_sender' value='' id="fb_sender"/>
<div id="fb_name"></div>
<div id="fb_questions">
</div>
</div>
<label for="additionalComments">Other comments:</label>
<textarea name='additionalComments' style="width:100%;height:80px;" id="additionalComments"></textarea>
<br />
<input type="button" id="btn_preview" value="Preview" class="right">


</div>


<div id="assessedDocument">
<div id="assessedDocumentMeta">
<p><strong>Filename:</strong> {$documentTitle}</p>
<p><strong>Folder:</strong> {$folderName}</p>
<p><strong>Simulation:</strong> {$projectName}</p>
<p><strong></strong></p>
</div>
</div>
{if $displayInline}
<div 
id="assessedDocumentContent">
<!--
<iframe src="{$documentUrl}" id="assessedDocumentContentFrame" >

</iframe>//-->
<object data="{$documentUrl}" id="assessedDocumentContentFrame"></object>
<!--<embed src="{$documentUrl}" id="assessedDocumentContentFrame" />//-->
</div>
{else}
<a href="{$documentUrl}">Click here to download the file</a>
{/if}
<!--{$documentContent}
</div>//-->

</div>
<div id="feedbackContainer">

<div style="float:right;cursor:pointer;" id="feedbackContainer_close">Close</div>
<h1>Feedback (preview)</h1>
<div id="feedbacktext" ></div>
<input type="button" id="btnSendFeedback" value="Send Feedback">
</div>
</body>

</html>
