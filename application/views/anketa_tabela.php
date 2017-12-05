<table class="table text-left">
  <?php foreach($surveyOptions as $surveyOption): ?>
    <?php
    if($votesNumber==0 || !isset($votesNumber)){
      $percent = 0;
    }
    else {
    $percent = $surveyOption->votes*100/$votesNumber;
    }

    ?>
    <tr>
      <td class="small">
        <input type="radio" name="vote" value="<?php echo $surveyOption->id; ?>"/> <?php echo $surveyOption->name; ?>
      </td>
      <td style="padding-top:13px" class="col-md-5">
        <div class="progress">
          <div class="progress-bar progress-bar-<?php echo $surveyOption->color;?>" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $percent; ?>%"><?php echo round($percent,1); ?>%
          </div>
        </div>
      </td>
    </tr>

  <?php endforeach; ?>

</table>
<?php if(isset($success)): ?>
  <p class="text-primary"><?php echo $success; ?></p>
<?php endif; ?>
