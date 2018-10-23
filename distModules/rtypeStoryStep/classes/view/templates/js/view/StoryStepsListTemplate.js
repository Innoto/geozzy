var geozzy = geozzy || {};
if(!geozzy.storystepsComponents) geozzy.storystepsComponents={};

geozzy.storystepsComponents.StorystepslistTemplate = ''+
'<div class="headSection clearfix">'+
  '<div class="row">'+
    '<div class="col-lg-6 col-md-12 clearfix">'+
      '<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">'+
          '<span class="sr-only">Toggle navigation</span>'+
          '<i class="fas fa-bars"></i>'+
      '</button>'+
      '<div class="headerTitleContainer">'+
        '<h3>Pasos <%- title_'+cogumelo.publicConf.langDefault+' %> </h3>'+
      '</div>'+
    '</div>'+
    '<div class="col-lg-6 col-md-12 clearfix">'+
      '<div class="headerActionsContainer">'+
        '<button type="button" class="addStoryStep btn btn-default"> Añadir paso</button>'+
        '<span class="saveChanges">'+
          '<button class="btn btn-danger cancel">Cancel</button>'+
          '<button class="btn btn-primary save">Save</button>'+
        '</span>'+
      '</div>'+
    '</div>'+
  '</div>'+
'</div>'+

'<div class="contentSection clearfix">'+
  '<div class="admin-cols-8-4">'+
    '<div class="row">'+
      '<div class="col-lg-8">'+
        '<div class="panel panel-default">'+
          '<div class="panel-heading">'+
              'Story steps for <%- title_'+cogumelo.publicConf.langDefault+' %>'+
          '</div>'+
          '<div class="panel-body">'+
            '<div id="storyStepsList" class="gzznestable dd">'+
              '<ol class="story dd-list">'+

              '</ol>'+
            '</div>'+
          '</div>'+
        '</div>'+
      '</div>'+
      '<div class="col-lg-4">'+
        '<div class="panel panel-default">'+
          '<div class="panel-heading">'+__('Pasos')+'</div>'+
          '<div class="panel-body">'+
            '<div class="text-content">'+
              '<p>'+__('Puedes dar orden a los pasos arrastrando sus correspondientes barras a la posición que les corresponda.')+'</p>'+
              '<p>'+__('Aquí podrás editar los pasos básicos de la historia')+'</p>'+
            '</div>'+
            '<a href="#resource/edit/id/<%- id %>" class="btn btn-primary pull-right">'+__('Editar historia')+'</a>'+
          '</div>'+
        '</div>'+
      '</div>'+
    '</div>'+
  '</div>'+
'</div>'+

'<div class="footerSection clearfix">'+
  '<div class="headerActionsContainer">'+
    '<button type="button" class="addStoryStep btn btn-default">Añadir paso</button>'+
    '<span class="saveChanges">'+
      '<button class="btn btn-danger cancel">Cancel</button>'+
      '<button class="btn btn-primary save">Save</button>'+
    '</span>'+
  '</div>'+
'</div>';
