<!DOCTYPE html>
<!-- defaultConHeader.tpl en app de Geozzy -->
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>{block name="headTitle"}galiciaagochada{/block}</title>

  {block name="headCssIncludes"}{$css_includes}{/block}
  {block name="headJsIncludes"}{$js_includes}{/block}


</head>
<body data-spy="scroll" data-target=".headContent">
  <article>
    <header class="headContent">
      {block name="headContent"}
        {include file="header.tpl"}
      {/block}
    </header>

    <section class="bodyContent">

      <select id="filter_gal_map" name="filter_gal_map">
        <option value="">Todas</option>
        <option
          value="baixominoVigo"
          data-coords="77,369,77,419,101,404,116,387,134,379,160,375,180,360,178,344,166,340,167,330,157,319,132,324,125,320,119,325"
          data-img="/media/module/rextAppZona/img/baixominoVigo.svg">
          Baixo Miño Vigo
        </option>
        <option
          value="terrasDePontevedra"
          data-coords="123,260,131,260,137,282,150,297,153,297,159,319,139,319,132,322,125,318,105,338,91,345,82,342,82,333,87,324,97,324,98,308,107,296,102,279,110,264"
          data-img="/media/module/rextAppZona/img/terrasDePontevedra.svg">
          Terras de Pontevedra
        </option>
        <option
          value="arousa"
          data-coords="53,279,62,274,70,264,70,256,86,249,89,253,96,247,107,246,123,259,107,264,101,281,106,298,97,309,96,317,84,317,65,299"
          data-img="/media/module/rextAppZona/img/arousa.svg">
          Arousa
        </option>
        <option
          value="costaDaMorte"
          data-coords="144,154,132,145,129,147,122,144,122,136,103,141,93,139,87,131,39,161,20,188,26,224,39,215,46,248,59,226,64,219,78,216,82,211,78,191,85,180,107,181,115,179,124,168,142,156,141,154"
          data-img="/media/module/rextAppZona/img/costaDaMorte.svg">
          Costa da Morte
        </option>
        <option
          value="aMarinaLucense"
          data-coords="247,55,257,50,279,58,296,68,310,86,340,91,337,111,327,126,332,135,314,146,307,143,307,125,297,128,282,126,278,111,266,110,262,107,265,98,263,97,254,105,246,100,237,92,246,55"
          data-img="/media/module/rextAppZona/img/aMarinaLucense.svg">
          A Mariña Lucense
        </option>
        <option
          value="ancaresCourel"
          data-coords="284,266,290,264,294,270,299,270,306,278,304,288,330,273,336,283,342,266,371,222,362,200,352,182,370,175,334,137,313,149,301,180,308,208,317,204,319,210,313,214,310,234,288,245,289,254,269,253,268,255,272,258"
          data-img="/media/module/rextAppZona/img/ancaresCourel.svg">
          Ancares - Courel
        </option>
        <option
          value="manzanedaTrevinca"
          data-coords="334,292,353,290,367,296,372,301,369,313,383,321,374,353,353,359,337,351,329,346,321,354,321,346,307,351,307,356,287,361,282,353,275,345,278,343,282,328,292,322,291,308,305,306,311,316,314,325,321,306"
          data-img="/media/module/rextAppZona/img/manzanedaTrevinca.svg">
          Manzaneda -Trevinca
        </option>
        <option
          value="ribeiraSacra"
          data-coords="212,279,218,258,221,254,230,252,239,222,252,221,264,235,275,237,268,254,282,267,280,269,284,272,288,269,305,279,300,289,308,292,327,277,329,275,334,283,332,291,321,305,315,324,306,304,290,308,292,321,282,327,276,346,281,352,276,354,269,343,261,334,265,331,259,329,255,334,250,329,242,331,244,324,242,320,236,321,233,319,236,314,230,306,225,304,225,299,227,295,218,291,222,285"
          data-img="/media/module/rextAppZona/img/ribeiraSacra.svg">
          Ribeira Sacra
        </option>
        <option
          value="verinViana"
          data-coords="247,408,252,414,261,414,257,424,272,422,274,416,279,418,283,429,294,421,319,411,320,399,326,393,332,403,352,399,350,378,341,379,354,360,329,349,321,355,317,349,309,355,288,361,282,354,278,364,268,369,267,384"
          data-img="/media/module/rextAppZona/img/verinViana.svg">
          Verín - Viana
        </option>
        <option
          value="celanovaAlimia"
          data-coords="186,426,201,426,207,419,217,416,220,421,249,409,244,406,265,384,264,363,278,361,278,353,258,351,251,366,240,362,237,366,224,363,213,356,207,341,194,337,189,359,177,361,180,379,170,406,181,424"
          data-img="/media/module/rextAppZona/img/celanovaAlimia.svg">
          Celanova - A Limia
        </option>
        <option
          value="terrasDeOurenseAllariz"
          data-coords="202,323,209,320,217,312,210,308,215,301,214,296,219,294,225,296,222,305,236,314,231,319,236,323,242,322,239,329,239,333,247,331,253,336,260,332,274,352,261,350,256,349,251,363,238,359,235,366,217,354,209,338,202,335"
          data-img="/media/module/rextAppZona/img/terrasDeOurenseAllariz.svg">
          Terras de Ourense - Allariz
        </option>
        <option
          value="oRibeiro"
          data-coords="179,281,184,288,202,287,205,283,216,282,218,286,215,291,209,308,216,314,202,321,201,336,192,336,187,358,182,360,180,341,167,341,169,330,162,321,152,301,169,289"
          data-img="/media/module/rextAppZona/img/oRibeiro.svg">
          O Ribeiro
        </option>
        <option
          value="dezaTabeiros"
          data-coords="147,246,157,241,160,233,167,237,177,234,179,225,197,231,208,230,205,244,220,254,216,259,211,280,205,279,201,285,185,287,179,279,154,299,137,284,132,261,132,259,122,256,120,250,127,246,142,247"
          data-img="/media/module/rextAppZona/img/dezaTabeiros.svg">
          dezaTabeiros
        </option>
        <option
          value="lugoTerraCha"
          data-coords="212,143,223,130,219,122,230,123,240,97,253,107,262,99,260,109,277,114,281,128,299,129,306,127,305,144,311,146,300,180,307,209,317,204,317,211,310,215,309,234,288,244,288,255,272,253,277,237,261,237,254,220,238,222,230,251,219,252,210,244,205,241,209,229,206,226,220,206,214,186,217,183,211,165"
          data-img="/media/module/rextAppZona/img/lugoTerraCha.svg">
          Lugo - Terra Chá
        </option>
        <option
          value="terrasDeSantiago"
          data-coords="148,157,145,169,157,174,183,176,190,170,208,165,216,184,212,187,218,207,203,226,208,229,179,224,176,233,166,236,161,231,155,241,139,246,127,245,117,252,107,246,96,246,97,230,86,226,85,214,79,191,86,182,107,182,114,181,124,167,143,156,144,154"
          data-img="/media/module/rextAppZona/img/terrasDeSantiago.svg">
          Terras de Santiago
        </option>
        <option
          value="murosNoia"
          data-coords="48,244,59,225,63,228,64,218,85,216,85,225,95,231,96,246,91,253,85,249,72,254,67,266,59,275,53,275"
          data-img="/media/module/rextAppZona/img/murosNoia.svg">
          Muros - Noia
        </option>
        <option
          value="ferrolTerra"
          data-coords="156,106,158,86,171,87,197,57,203,59,219,46,252,44,236,92,239,97,229,122,218,120,220,132,210,144,197,141,190,123,179,126,157,111"
          data-img="/media/module/rextAppZona/img/ferrolTerra.svg">
          Ferrol Terra
        </option>
        <option
          value="aCorunaAsMarinas"
          data-coords="147,122,166,117,179,127,187,124,196,142,212,146,209,165,192,169,183,176,147,169,150,156,144,151,132,146,122,145,122,130"
          data-img="/media/module/rextAppZona/img/aCorunaAsMarinas.svg">
          A coruña - As mariñas
        </option>
      </select>

    </section>

    <script>
      $(document).ready(function(){
        $('#filter_gal_map').zonaMap({
          width: 400,
          height: 500,
          imgSrc: '/media/module/rextAppZona/img/gal.svg',
          imgTransparent: '/media/module/rextAppZona/img/transparent.png'
        });
      });
    </script>
  </article>
</body>
</html>
