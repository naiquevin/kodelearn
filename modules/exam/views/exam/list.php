    <div class="r pagecontent">
        <div class="pageTop">
            <div class="pageTitle l">Exams</div>
            <div class="pageDesc r">this is a test description this is a test description this is a test description this is a test description this is a test description </div>
            <div class="clear"></div>
        </div><!-- pageTop -->
        <div class="topbar">
            <?php echo $links['add']?>
            
            <a onclick="$('#exam').submit();" class="pageAction r alert">Delete selected...</a>
            <span class="clear">&nbsp;</span>
        </div><!-- topbar -->
        <form name="exam" id="exam" method="POST" action="<?php echo $links['delete'] ?>">
        <table class="vm10 datatable fullwidth">
            <?php echo $table['heading']?>
            <?php foreach($table['data'] as $exam){ ?>
            <tr>
                <td><input type="checkbox" class="selected" name="selected" value="" /></td>
                <td><?php echo $exam->name ?></td>
                <td><?php echo $exam->examgroup->name ?></td>
                <td><?php echo date('d M Y H:i ', $exam->event->eventstart) ?></td>
                <td><?php echo $exam->course->name ?></td>
                <td><?php echo $exam->total_marks ?></td>
                <td><?php echo $exam->passing_marks ?></td>
                <td><?php echo ($exam->reminder)?'Yes':'No'; ?></td>
                <td>
                    <p><a href="#">View/ Edit</a></p>
                </td>
            </tr>
            <?php }?>
            <tr class="pagination">
                <td class="tar pagination" colspan="9">
                    <?php echo $pagination ?>
                </td>
            </tr>
        </table>
        </form>
    </div>
    <div class="clear"></div>
    