{assign scope="global" var="bodySection" value="blog"}


{block name="headCssIncludes" append}
<style type="text/css">
  .resImageHeader{
    background: rgba(0, 0, 0, 0) url("{$cogumelo.publicConf.mediaHost}cgmlImg/{$res.data.image.id}-a{$res.data.image.aKey}/fast/{$res.data.image.name}") no-repeat scroll center center / cover !important;
    height: 30vh;
    width: 100%;
  }
  @media screen and (min-width: 1200px) {
    .resImageHeader{
      background: rgba(0, 0, 0, 0) url("{$cogumelo.publicConf.mediaHost}cgmlImg/{$res.data.image.id}-a{$res.data.image.aKey}/blogLg/{$res.data.image.name}") no-repeat scroll center center / cover !important;
    }
  } /*1200px*/

  @media screen and (max-width: 1199px) {
    .resImageHeader{
      background: rgba(0, 0, 0, 0) url("{$cogumelo.publicConf.mediaHost}cgmlImg/{$res.data.image.id}-a{$res.data.image.aKey}/blogMd/{$res.data.image.name}") no-repeat scroll center center / cover !important;
    }
  }/*1199px*/

  @media screen and (max-width: 991px) {
    .resImageHeader{
      background: rgba(0, 0, 0, 0) url("{$cogumelo.publicConf.mediaHost}cgmlImg/{$res.data.image.id}-a{$res.data.image.aKey}/blogSm/{$res.data.image.name}") no-repeat scroll center center / cover !important;
    }
  }/*991px*/

  @media screen and (max-width: 767px) {
    .resImageHeader{
      background: rgba(0, 0, 0, 0) url("{$cogumelo.publicConf.mediaHost}cgmlImg/{$res.data.image.id}-a{$res.data.image.aKey}/blogXs/{$res.data.image.name}") no-repeat scroll center center / cover !important;
    }
  }/*767px*/
</style>
{/block}


<div class="resource resViewBlock {$res.data.rTypeIdName} res_{$res.data.id}" data-resource="{$res.data.id}">

  {if isset($res.data.image)}<section class="gzzSec resImageHeader"></section>{/if}

  {if count($blogEntryList)}
    <section class="gzzSec secBlogList">
      <div class="container">

        <div class="paginator" data-limit-page="{$limitPage}">
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
              {for $page=1 to $limitPage}
                <div class="page page-{$page}"><a href="{$res.data.urlAlias}?p={$page}">{$page}</a></div>
              {/for}
            </div>
          </div>
        </div>

        <div class="blogContainer">
        {foreach from=$blogEntryList item=blogEntry}
          <div class="individualBlog">
            <h5 class="date">{$blogEntry.timeCreation|date_format:"%e %B %Y"}</h5>
            <div class="infoBlog">
              <div class="row row-eq-height">
                <div class="col-md-4 col-xs-12">
                  <div class="imgBlog">
                    <img class="img-responsive" alt="{$blogEntry.title}"
                      src="/cgmlImg/{$blogEntry.image.id}-a{$blogEntry.image.aKey}/wsdpi16/{$blogEntry.image.name}">
                  </div>

                  {if $blogEntry.RExtSocialNetwork.activeFb || $blogEntry.RExtSocialNetwork.activeTwitter || $blogEntry.RExtSocialNetwork.activeGplus}
                    <div class="shareSocial">
                      <ul class="rextSocialNetworkContainer clearfix">
                      {if $blogEntry.RExtSocialNetwork.activeFb}
                        <li class="share-net facebook">
                          <a class="icon-share" target="_blank" rel="nofollow" href="http://www.facebook.com/sharer.php?u={$blogEntry.RExtSocialNetwork.url}&t={$blogEntry.RExtSocialNetwork.textFb|escape:'url'}">
                            <i class="fa fa-facebook" aria-hidden="true"></i>
                          </a>
                        </li>
                      {/if}
                      {if $blogEntry.RExtSocialNetwork.activeTwitter}
                        <li class="share-net twitter">
                          <a class="icon-share" target="_blank" rel="nofollow" href="http://twitter.com/share?url={$blogEntry.RExtSocialNetwork.url}&text={$blogEntry.RExtSocialNetwork.textTwitter|escape:'url'}{if isset($cogumelo.publicConf.socialNetworks.twitter)}&via={$cogumelo.publicConf.socialNetworks.twitter}{/if}">
                            <i class="fa fa-twitter" aria-hidden="true"></i>
                          </a>
                        </li>
                      {/if}
                      {if $blogEntry.RExtSocialNetwork.activeGplus}
                        <li class="share-net gplus">
                          <a class="icon-share" target="_blank" rel="nofollow" href="https://plus.google.com/share?url={$blogEntry.RExtSocialNetwork.url}" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=420');return false;">
                            <i class="fa fa-google-plus" aria-hidden="true"></i>
                          </a>
                        </li>
                      {/if}
                    </div>
                  {/if}
                </div>
                <div class="col-md-8 col-xs-12">
                  <div class="infoText">
                    <div class="row">
                      <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="text">
                          <h4 class="title">{$blogEntry.title}</h4>
                          <div class="mediumDescription">
                            {if isset($blogEntry.longDescription) && $blogEntry.longDescription !== ''}{$blogEntry.longDescription}{else}{$blogEntry.mediumDescription|default:''}{/if}
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="infoTags">
                        <div class="col-sm-3">
                          <div class="goToIndBlog">
                            <a class="btn btnProyecta" href="{$blogEntry.urlAlias}">{t}Leer más{/t}</a>
                          </div>
                        </div>
                        <div class="col-sm-9">
                          {if isset($blogEntry.terms)}
                            <ul class="tags">
                              {foreach from=$blogEntry.terms item=tag}
                                <li><i class="fa fa-asterisk" aria-hidden="true"></i> {$taxTerms[$tag].name}</li>
                              {/foreach}
                            </ul>
                          {/if}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        {/foreach}
        </div>

        <div class="loading" style="display:none;">
          <i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
          <span class="sr-only">{t}Cargando...{/t}</span>
        </div>

      </div>
    </section>
  {/if}

</div>
