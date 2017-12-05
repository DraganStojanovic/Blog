<?php if(isset($surveys)): ?>
<?php foreach($surveys as $paket): ?>
  <div class="col-md-3" id="anketa_main<?php echo $paket['survey']->id;?>">
    <div class="panel panel-info">
      <div class="panel-heading">
        <?php echo $paket['survey']->name; ?>
        <?php if($this->session->userdata('role')=='Admin'): ?>
          &nbsp&nbsp&nbsp&nbsp<a href="#anketa" onclick="edit_survey_generate(<?php echo $paket['survey']->id;?>)"><i class="fa fa-edit pull"></i></a>
        <?php endif; ?>
        <a href="#anketa" onclick="$('#anketa_main<?php echo $paket['survey']->id;?>').fadeOut('fast')"><i class="fa fa-close pull-right"></i></a>
      </div>
      <div class="panel-body">
        <div id="anketa<?php echo $paket['survey']->id; ?>" class="text-center">
          <table class="table text-left">
            <?php foreach($paket['surveyOptions'] as $surveyOption): ?>
              <?php
              if($paket['survey']->votesNumber==0){
                $percent = 0;
              }
              else {
              $percent = $surveyOption->votes*100/$paket['survey']->votesNumber;
              }

              ?>
              <tr>
                <td class="small">
                  <input type="radio" name="vote" value="<?php echo $surveyOption->id; ?>"/> <?php echo $surveyOption->name; ?>
                </td>
                <td style="padding-top:13px" class="col-md-5">
                  <div class="progress">
                    <div class="progress-bar progress-bar-<?php echo $surveyOption->color; ?>" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $percent; ?>%"><?php echo round($percent,1); ?>%
                    </div>
                  </div>
                </td>
              </tr>

            <?php endforeach; ?>
          </table>
          <div id="voteError<?php echo $paket['survey']->id;?>"></div>
        </div>
        <div class="text-center">

          <input type="button" id="voteButton<?php echo $paket['survey']->id;?>" class="btn btn-info center" onclick="vote(<?php echo $paket['survey']->id;?>)"; name="vote" value="Vote">
        </div>

      </div>
    </div>
  </div>


<?php endforeach; ?>
<?php endif; ?>
</div>
</div>
