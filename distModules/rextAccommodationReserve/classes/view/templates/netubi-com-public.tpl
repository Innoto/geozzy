
<!-- Reserve TPL -->
<div class="rextAccommodationReserve">
  <span class="anchorResource" id="reserveAnchor"></span>
  <h4>{t}Reserve{/t}</h4>
  <form action="">
    <div class="container">
      <div class="row">
        <div class="col-md-4 col-sm-6">
          <div class="form-group has-feedback">
            <label for="reserve_cal_{$rExt.data.idRelate}">{t}Check in date - Check out date{/t}</label>
            <input type="text" id="reserve_cal_{$rExt.data.idRelate}" class="form-control" readonly>
            <i class="fa fa-calendar form-control-feedback"></i>
          </div>
        </div>
        <div class="col-md-8 col-sm-6 mid">
          <div class="form-group">
            <a id="reserve_link_{$rExt.data.idRelate}" href="" class="btn btn-lg" type="button" target="_blank">{t}Reserve{/t}</a>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>


<script type="text/javascript">
/**
 *  RExtAccommodationReserve Info
 */
var geozzy = geozzy || {};
geozzy.rExtAccommodationReserveInfo = geozzy.rExtAccommodationReserveInfo || {
  srcUrl : '{$srcUrl}',
  idLink : '#reserve_link_{$rExt.data.idRelate}',
  idCal : '#reserve_cal_{$rExt.data.idRelate}',
  urlDateFormat : '{$channelInfo.patternDateFormat}',
  calDateFormat : '{$calDateFormat}'
}
</script>

<!-- /Reserve TPL -->
