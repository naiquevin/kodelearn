    <div class="r pagecontent">
        <div class="pageTop">
            <div class="pageTitle l">replace_here_page_title</div>
            <div class="pageDesc r">replace_here_page_description</div>
            <div class="clear"></div>
        </div><!-- pageTop -->
        <div class="pageContent">
            <p class="bm40">
                <input type="text" name="date" id="date" class="date" value="<?php echo $date; ?>"></input>
                <!-- <a class="button" href="#" id="add_users"> Add</a> -->
            </p>
            
            <p class="tip" style="display:none;" id="loading">Please wait...Loading Users</p>
            <div id="events-ajax"> 
                <?php echo $users ?>          
            </div>
        </div>
    </div>    
    <div class="clear"></div>
<script type="text/javascript"><!--
$('#date').change(function(){
	$('#loading').fadeIn();
    var date = $('#date').val();
    
    if(date){
        $.post(KODELEARN.config.base_url + "attendence/get_events", { "date": date },
        function(data){
           $('#events-ajax').html(data.response);
           $('#loading').fadeOut();                
        }, "json");
    }
	
});
//--></script>
