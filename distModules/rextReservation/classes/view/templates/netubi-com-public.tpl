
<!-- Reserve TPL -->
<div class="rextReservation">
  <span class="anchorResource" id="reserveAnchor"></span>
  <h4>{t}Reserve{/t}</h4>
  <form action="">
    <div class="row">
      <div class="col-md-6 col-lg-4">
        <div class="form-group has-feedback">
          <label for="reserve_cal_{$rExt.data.idRelate}">{t}Check in date - Check out date{/t}</label>
          <div class="input-group">
            <input type="text" id="reserve_cal_{$rExt.data.idRelate}" class="form-control" readonly>
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-calendar-alt form-control-feedback"></i></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-8 align-self-end">
        <a id="reserve_link_{$rExt.data.idRelate}" href="" class="btn btn-lg" type="button" target="_blank">{t}Reserve{/t}</a>
      </div>
    </div>
  </form>
</div>


<script type="text/javascript">
/**
 *  rextReservation Info
 */
var geozzy = geozzy || {};
geozzy.rextReservationInfo = geozzy.rextReservationInfo || {
  srcUrl : '{$srcUrl}',
  idLink : '#reserve_link_{$rExt.data.idRelate}',
  idCal : '#reserve_cal_{$rExt.data.idRelate}',
  urlDateFormat : '{$channelInfo.patternDateFormat}',
  calDateFormat : '{$calDateFormat}'
}
</script>

<!-- /Reserve TPL -->
