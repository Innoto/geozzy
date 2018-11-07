
<!-- Reserve TPL -->
<div class="rextReservation">
  <span class="anchorResource" id="reserveAnchor"></span>
  <h4>{t}Reserve{/t}</h4>
  <form action="">
    <div class="row">
      <div class="col-md-6 col-lg-4">
        <div class="form-group has-feedback">
          <label for="reserve_cal_{$rExt.data.idRelate}">{t}Check in date - Check out date{/t}</label>
          <input type="text" id="reserve_cal_{$rExt.data.idRelate}" class="form-control" readonly>
          <span class="glyphicon glyphicon-calendar form-control-feedback" aria-hidden="true"></span>
        </div>
      </div>
      <div class="col-md-6 col-lg-8">
        <div class="form-group">
          <a id="reserve_link_{$rExt.data.idRelate}" href="" class="btn btn-lg" type="button" target="_blank">{t}Reserve{/t}</a>
        </div>
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
