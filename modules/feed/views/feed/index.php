    <div class="r pagecontent">
        <div id="feeds">
            <?php if($feeds){ ?>
            <?php echo $feeds ?>
            <?php } else {?>
                <div class="vpad10">
                    No feed
                </div>
            <?php }?>
        </div>
        <div class="show_more ">
            <a id="more_feeds">show older feeds &#x25BC;</a>
        </div>
    </div>
    
<script type="text/javascript">
new verticalScroll({
    $link : $('#more_feeds'), 
    action : 'feeds',
    start : 6,
    controller : 'feed',
    $appendTO: $('#feeds') //Must Be Id  to which you want to append
});
</script>