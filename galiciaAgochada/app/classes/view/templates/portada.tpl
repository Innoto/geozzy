{extends file="primary.tpl"}

{block name="bodyContent"}
  <section class="gzzSec secImage">
    <!-- Carousel items -->
    <div id="carousel" class="s-carousel carousel carousel-fade" data-ride="carousel" data-interval="5000" data-pause="false">
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
      <a class="introLinkSegredos" href="#">{t}Descubre os nosos segredos{/t} <i class="fa fa-caret-down"></i></a>
    </div>
  </section>
  <section class="gzzSec secSegredos">
    <div class="container">
      <h2>{t}Os segredos de galicia{/t}</h2>
      <div class="row">
        <div class="segredoItem segredoRincons col-lg-4 col-sm-6">
          <a href="/rincons-con-encanto">
            <div class="segredoItemImg">
              <div class="icon"></div>
              <div class="trama"></div>
              <img class="img-responsive" src="/media/img/rincons.png">
              <div class="info">
                <h3>{t}Rincons con encanto{/t}</h3>
                <p>Nam in mauris nisi. Duis dictum auctor sapien in aliquam. Cras laoreet sem tortor, quis rutrum nibh sollicitudin eu. Nunc rutrum tellus nec leo suscipit, id consectetur mauris gravida</p>
              </div>
            </div>
          </a>
        </div>
        <div class="segredoItem segredoPaisaxes col-lg-4 col-sm-6">
          <a href="/paisaxes-espectaculares">
            <div class="segredoItemImg">
              <div class="icon"></div>
              <div class="trama"></div>
              <img class="img-responsive" src="/media/img/paisaxes.png">
              <div class="info">
                <h3>{t}Paisaxes Espectaculares{/t}</h3>
                <p>Nam in mauris nisi. Duis dictum auctor sapien in aliquam. Cras laoreet sem tortor, quis rutrum nibh sollicitudin eu. Nunc rutrum tellus nec leo suscipit, id consectetur mauris gravida</p>
              </div>
            </div>
          </a>
        </div>
        <div class="segredoItem segredoPraias col-lg-4 col-sm-6">
          <a href="/praias-ensono">
            <div class="segredoItemImg">
              <div class="icon"></div>
              <div class="trama"></div>
              <img class="img-responsive" src="/media/img/praias.png">
              <div class="info">
                <h3>{t}Praias de ensono{/t}</h3>
                <p>Nam in mauris nisi. Duis dictum auctor sapien in aliquam. Cras laoreet sem tortor, quis rutrum nibh sollicitudin eu. Nunc rutrum tellus nec leo suscipit, id consectetur mauris gravida</p>
              </div>
            </div>
          </a>
        </div>
        <div class="segredoItem segredoXantares col-lg-4 col-sm-6">
          <a href="/sabrosos-xantares">
            <div class="segredoItemImg">
              <div class="icon"></div>
              <div class="trama"></div>
              <img class="img-responsive" src="/media/img/xantares.png">
              <div class="info">
                <h3>{t}Sabrosos Xantares{/t}</h3>
                <p>Nam in mauris nisi. Duis dictum auctor sapien in aliquam. Cras laoreet sem tortor, quis rutrum nibh sollicitudin eu. Nunc rutrum tellus nec leo suscipit, id consectetur mauris gravida</p>
              </div>
            </div>
          </a>
        </div>
        <div class="segredoItem segredoAloxamentos col-lg-4 col-sm-6">
          <a href="/aloxamentos-con-encanto">
            <div class="segredoItemImg">
              <div class="icon"></div>
              <div class="trama"></div>
              <img class="img-responsive" src="/media/img/aloxamentos.png">
              <div class="info">
                <h3>{t}Aloxamentos con encanto{/t}</h3>
                <p>Nam in mauris nisi. Duis dictum auctor sapien in aliquam. Cras laoreet sem tortor, quis rutrum nibh sollicitudin eu. Nunc rutrum tellus nec leo suscipit, id consectetur mauris gravida</p>
              </div>
            </div>
          </a>
        </div>
        <div class="segredoItem segredoFestas col-lg-4 col-sm-6">
          <a href="#">
            <div class="segredoItemImg">
              <div class="icon"></div>
              <div class="trama"></div>
              <img class="img-responsive" src="/media/img/festas.png">
              <div class="info">
                <h3>{t}Festas{/t}</h3>
                <p>Nam in mauris nisi. Duis dictum auctor sapien in aliquam. Cras laoreet sem tortor, quis rutrum nibh sollicitudin eu. Nunc rutrum tellus nec leo suscipit, id consectetur mauris gravida</p>
              </div>
            </div>
          </a>
        </div>
      </div>
      <a class="segredosAll" href="#">{t}Descúbreos todos xuntos{/t} <i class="fa fa-long-arrow-right"></i></a>
    </div>
  </section>
  <section class="gzzSec secParticipa">
    <div class="container">
      <h2>{t}¿Algún outro segredo?{/t} <span>{t}¡Somos todo oídos!{/t}</span></h2>
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
            <span>(?) {t}qué lugares podo suxerir{/t}</span>
          </div>
        </div>

      </div>
    </div>
  </section>
  <section class="gzzSec secRecomendamos">
    <div class="container">

    </div>
  </section>
{/block}
