
<!-- Reserve TPL -->

<a id="reserve_link_{$rExt.data.idRelate}" href="" target="_blank"
>Ver disponibilidad</a> en las fechas <span id="reserve_cal_{$rExt.data.idRelate}"
style="font-weight: bold; text-decoration: underline; color: rgb(90, 183, 128);"> - </span>


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
