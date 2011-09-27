<form name="form" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<table class="data"  rules="cols">
<colgroup>
	<?php if ($this->col == 'title'): ?>
		<col />
		<col class="sort" />
		<col />
	<?php elseif($this->col == 'date'): ?>
		<col span="2" />
		<col class="sort" />
	<?php endif; ?>
</colgroup>
<thead>
<tr>
	<th scope="col">&nbsp;</th>
	<th scope="col"><a href="mods/bigbluebutton/index.php?<?php echo $this->orders[$this->order]; ?>=message"><?php echo _AT('bbb_message'); ?></a></th>
	<th scope="col"><a href="mods/bigbluebutton/index.php?<?php echo $this->orders[$this->order]; ?>=course_timing"><?php echo _AT('bbb_meeting_time'); ?></a></th>
	<th scope="col"><a href="mods/bigbluebutton/index.php?<?php echo $this->orders[$this->order]; ?>=join"><?php echo _AT('bbb_join'); ?></a></th>
</tr>
</thead>
<tfoot>
<tr>
	<td colspan="4"><input type="submit" name="edit" value="<?php echo _AT('edit'); ?>" class="button"/> <input type="submit" name="delete" value="<?php echo _AT('delete'); ?>"  class="button"/></td>
</tr>
</tfoot>
<tbody>
	<?php if ($row = mysql_fetch_assoc($this->result)): ?>
		<?php do { ?>
			<tr onkeydown="document.form['n<?php echo $row['news_id']; ?>'].checked = true; rowselect(this);" onmousedown="document.form['n<?php echo $row['message']; ?>'].checked = true; rowselect(this);" id="r_<?php echo $row['message']; ?>">
			
				<td><input type="radio" name="aid" value="<?php echo $row['message']; ?>" id="n<?php echo $row['course_timing']; ?>" /></td>
				
				<td><label for="n<?php echo $row['news_id']; ?>"><?php echo AT_print($row['message'], 'news.title'); ?></label></td>
				<td><?php echo $row['course_timing']; ?></td>
				<td><?php echo "<a href='".$this->bbb_joinURL."' target='_blank'>"._AT('bbb_join_conference'); ?></a></td>
			</tr>
		<?php } while ($row = mysql_fetch_assoc($this->result)); ?>
	<?php else: ?>
		<tr>
			<td colspan="4"><?php echo _AT('none_found'); ?></td>
		</tr>
	<?php endif; ?>
</tbody>
</table>
</form>