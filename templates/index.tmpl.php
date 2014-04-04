


<form name="form" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<input type="hidden" name="meetingId" value="<?php echo $row['meeting_id']; ?>" />
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
	<th scope="col"><a href="mods/bigbluebutton/index.php?<?php echo $this->orders[$this->order]; ?>=message"><?php echo _AT('bbb_message'); ?></a></th>
	<th scope="col"><a href="mods/bigbluebutton/index.php?<?php echo $this->orders[$this->order]; ?>=course_timing"><?php echo _AT('bbb_meeting_time'); ?></a></th>
	<th scope="col"><a href="mods/bigbluebutton/index.php?<?php echo $this->orders[$this->order]; ?>=join"><?php echo _AT('bbb_meeting_status'); ?></a></th>
	<th scope="col"><a href="mods/bigbluebutton/index.php?<?php echo $this->orders[$this->order]; ?>=join"><?php echo _AT('bbb_join'); ?></a></th>
	<?php if(BBB_MAX_RECORDINGS > 0){  ?>	
	<th scope="col"><a href="mods/bigbluebutton/index.php?<?php echo $this->orders[$this->order]; ?>=recording"><?php echo _AT('bbb_recordings'); ?></a></th>
	<?php } ?>
</tr>
</thead>
<tbody>
	<?php if(count($this->rows_meetings) > 0): ?>
		<?php 
		foreach($this->rows_meetings as $row){	
	/////////////////
	// Get the meeting info
	$infoParams = array(
		'meetingId' => $row['meeting_id'], 		// REQUIRED - We have to know which meeting.
		'password' => 'mp',			// REQUIRED - Must match moderator pass for meeting.
	
	);
	$itsAllGood = true;
	try {$response = $this->bbb->getMeetingInfoWithXmlResponseArray($infoParams);}
		catch (Exception $e) {
			echo 'Caught exception: ', $e->getMessage(), "\n";
			$itsAllGood = false;
		}
	
		if ($itsAllGood == true) {
			// If it's all good, then we've interfaced with our BBB php api OK:
			if ($response == null) {
				// If we get a null response, then we're not getting any XML back from BBB.
				echo "Failed to get any response. Maybe we can't contact the BBB server.";
			}	
		}	
	//////////////// end get meeting info
	
	
	$bbb_recordURL = bbb_get_recordings($row['meeting_id']);
	////////////////////
	// Set the meeting status 
	$response = bbb_is_meeting_running($row['meeting_id']);

			if($response['running'] == "true"){

				$meeting_status = "running";		
			} else if ($this->response[$i]['running'] == "false"){

				$meeting_status = "ended";
			}else{

			$meeting_status = "pending";
			}
			
	?>
		
			<tr onkeydown="document.form['n<?php echo $row['news_id']; ?>'].checked = true; rowselect(this);" onmousedown="document.form['n<?php echo $row['message']; ?>'].checked = true; rowselect(this);" id="r_<?php echo $row['message']; ?>">
				<td><label for="n<?php echo $row['news_id']; ?>"><?php echo html_entity_decode($row['message']); ?></label></td>
				<td><?php echo $row['course_timing']; ?></td>
				
				<?php if($row['status'] == "3") { ?>
						<td><?php echo _AT('bbb_meeting_ended'); ?></td>
				<?php } else { ?>
						<td><?php echo $meeting_status ?></td>	
					<?php } ?>			
				<?php if($_SESSION['is_admin'] > 0){ ?>				
				    <?php if($row['status'] == "3") { ?>
					    <td><?php echo _AT('bbb_meeting_ended'); ?></td>
				    <?php } else if($meeting_status == "running"){ ?>
					    <td><?php echo '<a href="mods/bigbluebutton/join_meeting.php?meetingId='.$row['meeting_id'].'">'._AT('bbb_join_conference'); ?></a>
					<?php	}else { ?>
					    <td><?php echo '<a href="mods/bigbluebutton/join_meeting_moderate.php?meetingId='.$row['meeting_id'].'">'._AT('bbb_join_conference'); ?></a> 
					
				    <?php if(BBB_MAX_RECORDINGS > 0){  ?>	
					    or 
				        <?php echo '<a href="mods/bigbluebutton/join_meeting_moderate.php?meetingId='.$row['meeting_id'].SEP.'record=true">'._AT('bbb_record_conference'); ?></a>
				    <?php } ?>
				        </td>
				    <?php } ?>
				<?php } else { ?>
				    <?php if($row['status'] == "3") { ?>
					    <td><?php echo _AT('bbb_meeting_ended'); ?></td>
				    <?php } else { ?>
					    <td><?php echo '<a href="mods/bigbluebutton/join_meeting.php?meetingId='.$row['meeting_id'].'">'._AT('bbb_join_conference'); ?></a> 
				    </td>
				    <?php } ?>
				<?php } ?>								
				<?php if(BBB_MAX_RECORDINGS > 0){  ?>
				    <?php if($bbb_recordURL != ''){ ?>
                        <td>
                        <?php 
                            $bbb_recordURL = bbb_get_recordings($row['meeting_id']); 
                        ?>
                        <a style="text-decoration:underline;" tabindex="0" onkeypress="javascript:window.open('<?php echo $bbb_recordURL; ?>', 'BBBWindow', 'width=850,height=800')" onclick="javascript:window.open('<?php echo $bbb_recordURL; ?>', 'BBBWindow', 'width=800,height=800')"><?php echo _AT('bbb_view_recording'); ?></a>
                        <!-- <?php echo '<a href="mods/bigbluebutton/view_meeting.php?view_meeting='.$row['meeting_id'].'" target="_top">'._AT('bbb_view_recording'); ?></a> -->
                        </td>
				    <?php }else{ ?>
				        <td><?php echo _AT('bbb_no_recording'); ?></td>
				    <?php } ?>
			    <?php } ?>
			</tr>
		<?php 
		    } // end foreach
		?>
	<?php else: ?>
		<tr>
			<td colspan="5"><?php echo _AT('none_found'); ?></td>
		</tr>
	<?php endif; ?>
</tbody>

</table>
</form>

