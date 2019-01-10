
<!-- Reserve TPL -->
<div class="rextReservation">
  <span class="anchorResource" id="reserveAnchor"></span>
  <h4>{t}Reserve{/t}</h4>
  <form action="">
    <div class="row">
      <div class="col-lg-4">
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
      <div class="col-lg-2">
        <div class="form-group">
          <label for="reserve_rooms_{$rExt.data.idRelate}">{t}Rooms{/t}</label>
          <select class="form-control" id="reserve_rooms_{$rExt.data.idRelate}">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
          </select>
        </div>
      </div>
      <div class="col-lg-2">
        <div class="form-group">
          <label for="reserve_adults_{$rExt.data.idRelate}">{t}Adults{/t}</label>
          <select class="form-control" id="reserve_adults_{$rExt.data.idRelate}">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
          </select>
        </div>
      </div>
      <div class="col-lg-4 mid">
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
geozzy.rExtReservationInfo = geozzy.rextReservationInfo || {
  srcUrl : '{$srcUrl}',
  idLink : '#reserve_link_{$rExt.data.idRelate}',
  idCal : '#reserve_cal_{$rExt.data.idRelate}',
  idRooms : '#reserve_rooms_{$rExt.data.idRelate}',
  idAdults : '#reserve_adults_{$rExt.data.idRelate}',
  urlDateFormat : '{$channelInfo.patternDateFormat}',
  calDateFormat : '{$calDateFormat}'
}
</script>

<!-- /Reserve TPL -->
