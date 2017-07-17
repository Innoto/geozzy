{assign scope="global" var="bodySection" value="about"}


{block name="headCssIncludes" append}
<style type="text/css">
  .resImageHeader{
    background: rgba(0, 0, 0, 0) url("{$cogumelo.publicConf.mediaHost}cgmlImg/{$res.data.image.id}-a{$res.data.image.aKey}/fast/{$res.data.image.name}") no-repeat scroll top center / cover !important;
    height: 40vh;
    width: 100%;
  }
  @media screen and (min-width: 1200px) {
    .resImageHeader{
      background: rgba(0, 0, 0, 0) url("{$cogumelo.publicConf.mediaHost}cgmlImg/{$res.data.image.id}-a{$res.data.image.aKey}/resourceLg/{$res.data.image.name}") no-repeat scroll top center / cover !important;
    }
  } /*1200px*/

  @media screen and (max-width: 1199px) {
    .resImageHeader{
      background: rgba(0, 0, 0, 0) url("{$cogumelo.publicConf.mediaHost}cgmlImg/{$res.data.image.id}-a{$res.data.image.aKey}/resourceMd/{$res.data.image.name}") no-repeat scroll top center / cover !important;
    }
  }/*1199px*/

  @media screen and (max-width: 991px) {
    .resImageHeader{
      background: rgba(0, 0, 0, 0) url("{$cogumelo.publicConf.mediaHost}cgmlImg/{$res.data.image.id}-a{$res.data.image.aKey}/resourceSm/{$res.data.image.name}") no-repeat scroll top center / cover !important;
    }
  }/*991px*/

  @media screen and (max-width: 767px) {
    .resImageHeader{
      background: rgba(0, 0, 0, 0) url("{$cogumelo.publicConf.mediaHost}cgmlImg/{$res.data.image.id}-a{$res.data.image.aKey}/resourceXs/{$res.data.image.name}") no-repeat scroll top center / cover !important;
    }
  }/*767px*/
</style>
{/block}

<div class="resource resViewBlock {$res.data.rTypeIdName} res_{$res.data.id}" data-resource="{$res.data.id}">

  <section class="gzzSec resImageHeader"></section>

  <section class="gzzSec resInfo">
    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
          <h1 class="title">{$res.data.title}</h1>
          <div class="description styleContent">{$res.data.content}</div>
        </div>
      </div>
      <div class="secActividades">
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-12">
            <h1 class="title">{t}Iniciativas y actividades{/t}</h1>
            <p class="content">
              {t}Con el ánimo de fomentar la innovación en las aulas, desde Proyecta ponemos a disposición de la comunidad escolar actividades y herramientas que favorezcan nuevas dinámicas en las aulas.{/t}
            </p>
            <p class="content">
              {t}Si eres profesor, Proyecta te ofrece, a través de su plataforma:{/t}
            </p>
          </div>
        </div>
        <div class="row row-eq-height">
          <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="infoActividad">
              <div class="imgActividad">
                <img class="img-responsive" src="{$cogumelo.publicConf.media}/img/queEsProyecta/encuentros-inspiratics.png" alt="{t}Encuentros InspiraTICs{/t}">
              </div>
              <div class="textActividad">
                <h4 class="title">{t}Encuentros InspiraTICs{/t}</h4>
                <p class="content">
                  {t}Encuentros trimestrales para profesores preuniversitarios, pensados para que “tus alumnos atiendan, entiendan y aprendan”.{/t}
                </p>
              </div>
            </div>
          </div>
          <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="infoActividad">
              <div class="imgActividad">
                <img class="img-responsive" src="{$cogumelo.publicConf.media}/img/queEsProyecta/concursos.png" alt="{t}Concursos{/t}">
              </div>
              <div class="textActividad">
                <h4 class="title">{t}Concursos{/t}</h4>
                <p class="content">
                  {t}Concurso Proyecta Innovación, dirigido a docentes preuniversitarios de Galicia, que quieran desarrollar en sus centros proyectos que supongan una renovación del aprendizaje de sus alumnos.{/t}
                  {t}Certamen Proyecta D+I, convocatoria nacional para docentes preuniversitarios que enseñen de forma diferente.{/t}
                </p>
              </div>
            </div>
          </div>
          <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="infoActividad">
              <div class="imgActividad">
                <img class="img-responsive" src="{$cogumelo.publicConf.media}/img/queEsProyecta/recursos-inspiracion.png" alt="{t}Recursos e inspiración{/t}">
              </div>
              <div class="textActividad">
                <h4 class="title">{t}Recursos e inspiración{/t}</h4>
                <p class="content">
                  {t}En nuestra web puedes encontrar recursos, artículos y videotutoriales realizados por nuestra red de expertos en  materia de tecnología y educación.{/t}
                </p>
              </div>
            </div>
          </div>
          <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="infoActividad">
              <div class="imgActividad">
                <img class="img-responsive" src="{$cogumelo.publicConf.media}/img/queEsProyecta/comunidad-proyecta.png" alt="{t}Comunidad Proyecta{/t}">
              </div>
              <div class="textActividad">
                <h4 class="title">{t}Comunidad Proyecta{/t}</h4>
                <p class="content">
                  {t}Participando en nuestras actividades o concursos entrarás a formar parte de la Comunidad Proyecta. Esto te permitirá estar informado de las actividades que organicemos, boletines y otros recursos elaborados por nuestros colaboradores.{/t}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="gzzSec secSocialNetworksContact">
    <div class="container">
      <h2>{t}Más información{/t}</h2>
      <div class="row row-eq-height">
        <div class="col-xs-12 col-sm-12 col-md-6">
          <p class="content">
            {t}Puedes seguirnos en nuestros diferentes canales sociales y, si lo prefieres, suscribirte al Boletín Proyecta, donde recibirás información directamente en tu e-mail.{/t}
          </p>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6">
          <ul class="socialNetwork">
            <li class="rsAbout rsFacebook"><a href="https://www.facebook.com/plataformaproyecta" target="_blank" data-toggle="tooltip" data-placement="bottom" title="/plataformaproyecta"><i class="fa fa-fw fa-3x fa-facebook" aria-hidden="true"></i></a></li>
            <li class="rsAbout rsTwitter"><a href="https://twitter.com/proyectaensino" target="_blank" data-toggle="tooltip" data-placement="bottom" title="@proyectaensina"><i class="fa fa-fw fa-3x fa-twitter" aria-hidden="true"></i></a></li>
            <li class="rsAbout rsYoutube"><a href="https://www.youtube.com/plataformaproyecta" target="_blank" data-toggle="tooltip" data-placement="bottom" title="/plataformaproyecta"><i class="fa fa-fw fa-3x fa-youtube" aria-hidden="true"></i></a></li>
            <li class="rsAbout rsGooglePlus"><a href="https://plus.google.com/u/0/+PlataformaProyecta/posts" target="_blank" data-toggle="tooltip" data-placement="bottom" title="/+PlataformaProyecta"><i class="fa fa-fw fa-2x fa-google-plus" aria-hidden="true"></i></a></li>
            <li class="rsAbout rsEmail"><a href="mailto:info@plataformaproyecta.org" data-toggle="tooltip" data-placement="bottom" title="info@plataformaproyecta.org"><i class="fa fa-fw fa-3x fa-envelope" aria-hidden="true"></i></a></li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  {if isset($collectionByType.multimedia)}
    <section class="gzzSec secCarouselMultimedia">
      <div class="container">
        {foreach key=key item=col from=$collectionByType.multimedia}
          <h2>{$col.col.title}</h2>
          <div class="row">
            <div class="col-xs-12">
              <div id="multimediaGallery_{$key}" class="carouselMultimediaGallery">
                {foreach from=$col['res'] item=rc}
                  {if $rc.multimediaUrl}
                    <img alt="{$rc.title}" src="{$rc.image}"
                      data-type="youtube"
                      data-image="{$rc.image_big}"
                      data-videoid="{$rc.multimediaUrl}"
                      data-description="{$rc.title}">
                  {else}
                    <img alt="{$rc.title}" src="{$rc.image}"
                      data-image="{$rc.image_big}"
                      data-description="{$rc.title}">
                  {/if}
                {/foreach}
              </div>
            </div>
          </div>
        {/foreach}
      </div>
    </section>
  {/if}

</div>
