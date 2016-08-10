
<!-- Reserve TPL -->

<a id="reserve_link_{$rExt.data.idRelate}" href="" target="_blank">Ver disponibilidad</a>
 en las fechas <span id="reserve_cal_{$rExt.data.idRelate}"
style="font-weight: bold; text-decoration: underline; color: rgb(90, 183, 128);"> - </span>
 para <input id="reserve_adults_{$rExt.data.idRelate}" type="text" style="width: 3em; text-align: right;"> personas
 en <input id="reserve_rooms_{$rExt.data.idRelate}" type="text" style="width: 3em; text-align: right;"> habitaciones.


<script type="text/javascript">
/**
 *  RExtAccommodationReserve Info
 */
var geozzy = geozzy || {};
geozzy.rExtAccommodationReserveInfo = geozzy.rExtAccommodationReserveInfo || {
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
