
  <span id="inicio"></span>
  <section class="gzzSec secImage">
    <!-- Carousel items -->
    <div id="carousel" class="s-carousel carousel slide carousel-fade" data-ride="carousel" data-interval="5000" data-pause="false">
      <!--<ol class="carousel-indicators">
        <li data-target="#carousel" data-slide-to="0" class="active"></li>
        <li data-target="#carousel" data-slide-to="1"></li>
        <li data-target="#carousel" data-slide-to="2"></li>
        <li data-target="#carousel" data-slide-to="3"></li>
      </ol>-->
      <div class="carousel-inner">
        <div class="item active"></div>
        <div class="item"></div>
        <div class="item"></div>
        <div class="item"></div>
      </div>
      <div class="trama"></div>
    </div>

    <div class="container">
      <div class="intro">
        <h2>{t}Galicia{/t}</h2>
        <p>{t}Das postas do sol en Lapamán ás vistas de Saiáns{/t}</p>
        <p>{t}Ruta polos mellores chiringuitos de Galicia{/t}</p>
      </div>
      <a class="introLinkSegredos" href="#segredos">{t}Descubre os nosos segredos{/t} <i class="fa fa-caret-down"></i></a>
    </div>
  </section>

  <span class="anchor" id="segredos"></span>
  <section class="gzzSec secSegredos">
    <div class="container">
      <h2>{t}Os segredos de Galicia{/t}</h2>
      <div class="row">
        <div class="segredoItem segredoRincons col-lg-4 col-sm-6">
          <a href="{$explorersInfo['rinconsExplorer']['url']}">
            <div class="segredoItemImg">
              <div class="icon"></div>
              <div class="trama"></div>
              <img class="img-responsive" src="{$cogumelo.publicConf.media}/img/rincons.png">
              <div class="info">
                <h3>{$explorersInfo['rinconsExplorer']['title']}</h3>
                <p>{$explorersInfo['rinconsExplorer']['shortDescription']}</p>
              </div>
            </div>
          </a>
        </div>
        <div class="segredoItem segredoPaisaxes col-lg-4 col-sm-6">
          <a href="{$explorersInfo['paisaxesExplorer']['url']}">
            <div class="segredoItemImg">
              <div class="icon"></div>
              <div class="trama"></div>
              <img class="img-responsive" src="{$cogumelo.publicConf.media}/img/paisaxes.png">
              <div class="info">
                <h3>{$explorersInfo['paisaxesExplorer']['title']}</h3>
                <p>{$explorersInfo['paisaxesExplorer']['shortDescription']}</p>
              </div>
            </div>
          </a>
        </div>
        <div class="segredoItem segredoPraias col-lg-4 col-sm-6">
          <a href="{$explorersInfo['praiasExplorer']['url']}">
            <div class="segredoItemImg">
              <div class="icon"></div>
              <div class="trama"></div>
              <img class="img-responsive" src="{$cogumelo.publicConf.media}/img/praias.png">
              <div class="info">
                <h3>{$explorersInfo['praiasExplorer']['title']}</h3>
                <p>{$explorersInfo['praiasExplorer']['shortDescription']}</p>
              </div>
            </div>
          </a>
        </div>
        <div class="segredoItem segredoXantares col-lg-4 col-sm-6">
          <a href="{$explorersInfo['xantaresExplorer']['url']}">
            <div class="segredoItemImg">
              <div class="icon"></div>
              <div class="trama"></div>
              <img class="img-responsive" src="{$cogumelo.publicConf.media}/img/xantares.png">
              <div class="info">
                <h3>{$explorersInfo['xantaresExplorer']['title']}</h3>
                <p>{$explorersInfo['xantaresExplorer']['shortDescription']}</p>
              </div>
            </div>
          </a>
        </div>
        <div class="segredoItem segredoAloxamentos col-lg-4 col-sm-6">
          <a href="{$explorersInfo['aloxamentosExplorer']['url']}">
            <div class="segredoItemImg">
              <div class="icon"></div>
              <div class="trama"></div>
              <img class="img-responsive" src="{$cogumelo.publicConf.media}/img/aloxamentos.png">
              <div class="info">
                <h3>{$explorersInfo['aloxamentosExplorer']['title']}</h3>
                <p>{$explorersInfo['aloxamentosExplorer']['shortDescription']}</p>
              </div>
            </div>
          </a>
        </div>
        <div class="segredoItem segredoFestas col-lg-4 col-sm-6">
          <a href="{$explorersInfo['festasExplorer']['url']}">
            <div class="segredoItemImg">
              <div class="icon"></div>
              <div class="trama"></div>
              <img class="img-responsive" src="{$cogumelo.publicConf.media}/img/festas.png">
              <div class="info">
                <h3>{$explorersInfo['festasExplorer']['title']}</h3>
                <p>{$explorersInfo['festasExplorer']['shortDescription']}</p>
              </div>
            </div>
          </a>
        </div>
      </div>
      <a class="segredosAll" href="{$explorersInfo['segredosExplorer']['url']}">{t}Descúbreos todos xuntos{/t} <i class="fa fa-long-arrow-right"></i></a>
    </div>
  </section>

  <span class="anchor" id="participa"></span>
  <section class="gzzSec secParticipa">
    <div class="container">
      <h2>{t}Algún outro segredo?{/t} <span>{t}Somos todo ouvidos!{/t}</span></h2>
      <p>{t}Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut nec justo et odio tempus eleifend. Sed gravida pharetra sagittis. Aliquam vel tempus lacus. Morbi a nibh convallis, laoreet massa eget, consectetur lorem. {/t}</p>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut nec justo et odio tempus eleifend.</p>

      <div class="propose">
        <div class="row">
          <div class="col-lg-6">
            <select>
              <option value="1">Option1</option>
              <option value="2">Option2</option>
              <option value="3">Option3</option>
              <option value="4">Option4</option>
            </select>
          </div>
          <div class="col-lg-6">
            <button>{t}Propoñer{/t}</button>
            <span>(?) {t}Qué lugares podo suxerir?{/t}</span>
          </div>
        </div>

      </div>
    </div>
  </section>

  {$l = $cogumelo.publicConf.C_LANG}
  <span class="anchor" id="recomendamos"></span>
  <section class="gzzSec secRecomendamos">
    <div class="container">
      {if isset($rdRecantosConEstilo) && $rdRecantosConEstilo!=false}
        <div class="destContainer destRecantosConEstilo">
          <h2>{t}Recantos con Estilo{/t}</h2>
          <div class="owl-carousel owl-carousel-gzz">
            {foreach from=$rdRecantosConEstilo item=rd}
              <div class="item">
                <div class="itemImage">
                  <img class="img-responsive" src="{$cogumelo.publicConf.mediaHost}cgmlImg/{$rd.data.image}/fast_cut/{$rd.data.image}.jpg">
                  <div class="trama">
                    <div class="destResourceMoreInfo">
                      <p>{$rd.data["shortDescription_$l"]}</p>
                      <a href="/{$rd.urlAlias}" class="btn btn-primary">{t}Descúbreo{/t}</a>
                    </div>
                  </div>
                </div>
                <div class="itemTitle">
                  <a href="/{$rd.urlAlias}">
                    <h3>{$rd.data["title_$l"]}</h3>
                  </a>
                </div>
              </div>
            {/foreach}
          </div>
        </div>
      {/if}
      {if isset($rdFestaRachada) && $rdFestaRachada!=false}
        <div class="destContainer destFestaRachada">
          <h2>{t}Festa Rachada{/t}</h2>
          <div class="owl-carousel owl-carousel-gzz">
            {foreach from=$rdFestaRachada item=rd}
              <div class="item">
                <div class="itemImage">
                  <img class="img-responsive" src="{$cogumelo.publicConf.mediaHost}cgmlImg/{$rd.data.image}/fast_cut/{$rd.data.image}.jpg">
                  <div class="trama" >
                    <div class="destResourceMoreInfo">
                      <p>{$rd.data["shortDescription_$l"]}</p>
                      <a href="/{$rd.urlAlias}" class="btn btn-primary">{t}Descúbreo{/t}</a>
                    </div>
                  </div>
                </div>
                <div class="itemTitle">
                  <a href="/{$rd.urlAlias}">
                    <h3>{$rd.data["title_$l"]}</h3>
                  </a>
                </div>
              </div>
            {/foreach}
          </div>
        </div>
      {/if}
      {if isset($rdPraiasDeEnsono) && $rdPraiasDeEnsono!=false }
        <div class="destContainer destPraiasDeEnsono">
          <h2>{t}Praias de Ensono{/t}</h2>
          <div class="owl-carousel owl-carousel-gzz">
            {foreach from=$rdPraiasDeEnsono item=rd}
              <div class="item">
                <div class="itemImage">
                  <img class="img-responsive" src="{$cogumelo.publicConf.mediaHost}cgmlImg/{$rd.data.image}/fast_cut/{$rd.data.image}.jpg">
                  <div class="trama">
                    <div class="destResourceMoreInfo">
                      <p>{$rd.data["shortDescription_$l"]}</p>
                      <a href="/{$rd.urlAlias}" class="btn btn-primary">{t}Descúbreo{/t}</a>
                    </div>
                  </div>
                </div>
                <div class="itemTitle">
                  <a href="/{$rd.urlAlias}">
                    <h3>{$rd.data["title_$l"]}</h3>
                  </a>
                </div>
              </div>
            {/foreach}
          </div>
        </div>
      {/if}

      {if isset($rdPaisaxesEspectaculares) && $rdPaisaxesEspectaculares!=false }
        <div class="destContainer destPaisaxesEspectaculares">
          <h2>{t}Paisaxes Espectaculares{/t}</h2>
          <div class="owl-carousel owl-carousel-gzz">
            {foreach from=$rdPaisaxesEspectaculares item=rd}
              <div class="item">
                <div class="itemImage">
                  <img class="img-responsive" src="{$cogumelo.publicConf.mediaHost}cgmlImg/{$rd.data.image}/fast_cut/{$rd.data.image}.jpg">
                  <div class="trama">
                    <div class="destResourceMoreInfo">
                      <p>{$rd.data["shortDescription_$l"]}</p>
                      <a href="/{$rd.urlAlias}" class="btn btn-primary">{t}Descúbreo{/t}</a>
                    </div>
                  </div>
                </div>
                <div class="itemTitle">
                  <a href="/{$rd.urlAlias}">
                    <h3>{$rd.data["title_$l"]}</h3>
                  </a>
                </div>
              </div>
            {/foreach}
          </div>
        </div>
      {/if}
      {if isset($rdAloxamentoConEncanto) && $rdAloxamentoConEncanto!=false }
        <div class="destContainer destAloxamentoConEncanto">
          <h2>{t}Aloxamentos con Encanto{/t}</h2>
          <div class="owl-carousel owl-carousel-gzz">
            {foreach from=$rdAloxamentoConEncanto item=rd}
              <div class="item">
                <div class="itemImage">
                  <img class="img-responsive" src="{$cogumelo.publicConf.mediaHost}cgmlImg/{$rd.data.image}/fast_cut/{$rd.data.image}.jpg">
                  <div class="trama">
                    <div class="destResourceMoreInfo">
                      <p>{$rd.data["shortDescription_$l"]}</p>
                      <a href="/{$rd.urlAlias}" class="btn btn-primary">{t}Descúbreo{/t}</a>
                    </div>
                  </div>
                </div>
                <div class="itemTitle">
                  <a href="/{$rd.urlAlias}">
                    <h3>{$rd.data["title_$l"]}</h3>
                  </a>
                </div>
              </div>
            {/foreach}
          </div>
        </div>
      {/if}
      {if isset($rdAutenticaGastronomia) && $rdAutenticaGastronomia!=false }
      <div class="destContainer destAutenticaGastronomia">
        <h2>{t}Auténtica Gastronomía{/t}</h2>
        <div class="owl-carousel owl-carousel-gzz">
          {foreach from=$rdAutenticaGastronomia item=rd}
            <div class="item">
              <div class="itemImage">
                <img class="img-responsive" src="{$cogumelo.publicConf.mediaHost}cgmlImg/{$rd.data.image}/fast_cut/{$rd.data.image}.jpg">
                <div class="trama">
                  <div class="destResourceMoreInfo">
                    <p>{$rd.data["shortDescription_$l"]}</p>
                    <a href="/{$rd.urlAlias}" class="btn btn-primary">{t}Descúbreo{/t}</a>
                  </div>
                </div>
              </div>
              <div class="itemTitle">
                <a href="/{$rd.urlAlias}">
                  <h3>{$rd.data["title_$l"]}</h3>
                </a>
              </div>
            </div>
          {/foreach}
        </div>
      </div>
      {/if}
    </div>
  </section>
