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
	<th scope="col"><a href="mods/bigbluebutton/index.php?<?php echo $this->orders[$this->order]; ?>=message"><?php echo _AT('bbb_meeting_name'); ?></a></th>
	<th scope="col"><a href="mods/bigbluebutton/index.php?<?php echo $this->orders[$this->order]; ?>=message"><?php echo _AT('bbb_message'); ?></a></th>
	<th scope="col"><a href="mods/bigbluebutton/index.php?<?php echo $this->orders[$this->order]; ?>=course_timing"><?php echo _AT('bbb_meeting_time'); ?></a></th>
	<th scope="col"><a href="mods/bigbluebutton/index.php?<?php echo $this->orders[$this->order]; ?>=status"><?php echo _AT('bbb_meeting_status'); ?></a></th>
	<th scope="col"><a href="mods/bigbluebutton/index.php?<?php echo $this->orders[$this->order]; ?>=join"><?php echo _AT('bbb_join'); ?></a></th>
	<th scope="col"><a href="mods/bigbluebutton/index.php?<?php echo $this->orders[$this->order]; ?>=recording"><?php echo _AT('bbb_recordings'); ?></a></th>
</tr>
</thead>
<tfoot>
<tr>
	<td colspan="7"><input type="submit" name="edit" value="<?php echo _AT('edit'); ?>" class="button"/> <input type="submit" name="delete" value="<?php echo _AT('delete'); ?>"  class="button"/></td>
</tr>
</tfoot>
<tbody>
	<?php if ($row = mysql_fetch_assoc($this->result)): 
		$i = 0;
	
	/////////////////////
	// Output a row for each available meeting	
	 do { 
		
	/////////////////
	// Get the meeting info
	
	bbb_get_meeting_info($row['meeting_id']);
	
	/////////////////
	/*
	$infoParams = array(
		'meetingId' => $_SERVER['HTTP_HOST'].'-'.$row['meeting_id'], 		// REQUIRED - We have to know which meeting.
		'password' => 'mp',			// REQUIRED - Must match attendee pass for meeting.
	
	);
	//debug($infoParams);
	$itsAllGood = true;
	try {$response = $this->bbb->getMeetingInfoWithXmlResponseArray($infoParams);}
		catch (Exception $e) {
			echo 'Caught exception -g: ', $e->getMessage(), "\n";
			$itsAllGood = false;
		}
	
		if ($itsAllGood == true) {
			// If it's all good, then we've interfaced with our BBB php api OK:
			if ($response == null) {
				// If we get a null response, then we're not getting any XML back from BBB.
				echo "Failed to get any response. Maybe we can't contact the BBB server.";
			}	
		}	
		*/
	//	debug($response);
	//////////////// end get meeting info
	
	
	////////////////
	// Get meeting recordings
	
	$bbb_recordURL = bbb_get_recordings($row['meeting_id']);
//global $bbb_recordURL;
//	debug($bbb_recordURL);
	////////////////
/*
	$recordingsParams = array('meetingId' => $_SERVER['HTTP_HOST'].'-'.$row['meeting_id']);			// OPTIONAL - comma separate if multiples

	try {$recording = $this->bbb->getRecordingsWithXmlResponseArray($recordingsParams);}
		catch (Exception $e) {
			echo 'Caught exception -f: ', $e->getMessage(), "\n";
			$itsAllGood = false;
		}

	if ($itsAllGood == true) {
		// If it's all good, then we've interfaced with our BBB php api OK:
		if ($recording == null) {
			// If we get a null response, then we're not getting any XML back from BBB.
			echo "Failed to get any response. Maybe we can't contact the BBB server.";
		}	
	}	

	if($recording['0']['playbackFormatUrl'] != ''){
		 $bbb_recordURL = $recording['0']['playbackFormatUrl'] ;
	}
	*/
//debug($recordingsParams);
//debug($bbb_recordURL);
	///////////////////// end get meeting recordings
		
	////////////////////
	// Set the meeting status 
		if($this->response[$i]['meetingId'] == $_SERVER['HTTP_HOST']."-".$row['meeting_id']){
			if($this->response[$i]['running'] == "true"){
				$meeting_status = _AT('bbb_meeting_running');		
			} else if ($this->response[$i]['running'] == "false"){
				$meeting_status = _AT('bbb_meeting_over');
			}
				
		} else {
			$meeting_status = _AT('bbb_meeting_pending');
		}
	///////////////////// end meeting status
		?>
			<tr onkeydown="document.form['n<?php echo $row['news_id']; ?>'].checked = true; rowselect(this);" onmousedown="document.form['n<?php echo $row['message']; ?>'].checked = true; rowselect(this);" id="r_<?php echo $row['message']; ?>">
			
				<td>
				<input type="hidden" name="meetingId" value="<?php echo $row['meeting_id']; ?>" />
				<input type="radio" name="aid" value="<?php echo $row['meeting_id']; ?>" id="<?php echo $row['meeting_id']; ?>" /></td>
				<td><label for="<?php echo $row['meeting_id']; ?>"><?php echo $row['course_name']; ?></label></td>
				<td><?php echo htmlentities_utf8($row['message']); ?></td>
				<td><?php echo $row['course_timing']; ?></td>
				<?php if($row['status'] == "3") { ?>
						<td><?php echo _AT('bbb_meeting_ended'); ?></td>
				<?php } else { ?>
						<td><?php echo $meeting_status ?></td>	
					<?php } ?>
					
				<?php if($row['status'] == "3") { ?>
					<td><?php echo _AT('bbb_meeting_ended'); ?></td>
				<?php } else { ?>
					<td><?php echo '<a href="mods/bigbluebutton/join_meeting_moderate.php?meetingId='.$row['meeting_id'].'">'._AT('bbb_join_conference'); ?></a> or 
				<?php echo '<a href="mods/bigbluebutton/join_meeting_moderate.php?meetingId='.$row['meeting_id'].SEP.'record=true">'._AT('bbb_record_conference'); ?></a>
				</td>
				<?php } ?>
					
				<?php if($bbb_recordURL != ''){ ?>
				<td><?php echo '<a href="mods/bigbluebutton/view_meeting.php?view_meeting='.$row['meeting_id'].'" target="_top">'._AT('bbb_view_recording'); ?></a><!-- <a href="mods/bigbluebutton/pub_meeting.php?pub_meeting=<?php echo $row['meeting_id'];?>" target="_top"><?php echo _AT('bbb_publish_recording'); ?></a> -->
				<a href="<?php echo $_SERVER['PHP_SELF']; ?>?delete_meeting=<?php echo $row['meeting_id']; ?>" >
				<img src="<?php echo $this->base_href; ?>/images/x.gif" alt="<?php echo _AT('bbb_delete_recording'); ?>" title="<?php echo _AT('bbb_delete_recording'); ?>" style="float:right;"/></a></td>
				<?php }else{ ?>
				<td><?php echo _AT('bbb_no_recording'); ?></td>
				<?php } ?>
			</tr>
		<?php 
			$i = $i++;
			} while ($row = mysql_fetch_assoc($this->result)); 
			
	////////////////// end output of each available meeting
			?>

			
	<?php else: ?>
		<tr>
			<td colspan="6"><?php echo _AT('none_found'); ?></td>
		</tr>
	<?php endif; ?>
</tbody>
</table>
</form>

<?php
/*
$itsAllGood = true;
try {$result = $this->bbb->getMeetingsWithXmlResponseArray();}
	catch (Exception $e) {
		echo 'Caught exception -e: ', $e->getMessage(), "\n";
		$itsAllGood = false;
	}

if ($itsAllGood == true) {
	// If it's all good, then we've interfaced with our BBB php api OK:
	if ($result == null) {
		// If we get a null response, then we're not getting any XML back from BBB.
		echo "Failed to get any response. Maybe we can't contact the BBB server.";
	}	
	else { 
	// We got an XML response, so let's see what it says:
		if ($result['returncode'] == 'SUCCESS') {
			// Then do stuff ...
			echo "<p>We got some meeting info from BBB:</p>";
			// You can parse this array how you like. For now we just do this:
			debug($result);
		}
		else {
			echo "<p>We didn't get a success response. Instead we got this:</p>";
			debug($result);
		}
	}
}
*/
?>