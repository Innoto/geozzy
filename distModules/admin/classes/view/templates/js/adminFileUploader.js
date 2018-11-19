


var adminFileUploader={};

$(document).ready( function(){
  elfNode = $('<div>');

  elfNode.dialog({
    autoOpen: false,
    modal: true,
    width: '80%',

    create: function (event, ui) {
      var startPathHash = (elfDirHashMap[dialogName] && elfDirHashMap[dialogName])? elfDirHashMap[dialogName] : '';
      // elFinder configure
       elfInsrance = $(this).elfinder( elfinderOptions ).elfinder('instance');
       hideElFinderToolbars();
    },
    open: function() {
      elfNode.find('div.elfinder-toolbar input').blur();
      setTimeout(function(){
        elfInsrance.enable();
      }, 100);
      hideElFinderToolbars();
    },
    resizeStop: function() {
      elfNode.trigger('resize');
      hideElFinderToolbars();
    }
  }).parent().css({'zIndex':'11000'});

});

var elfNode, elfInsrance, dialogName,
    elfUrl				= '/admin/filemanagerbackend', // Your connector's URL
    elfDirHashMap = {},
    customData = {},
    elfinderOptions = {



      url: elfUrl,
      useBrowserHistory:false,
      getFileCallback: function (file) {
        var url = file.url;
        var dialog = CKEDITOR.dialog.getCurrent();
        if (dialogName == 'image') {
          var urlObj = 'txtUrl';
        } else if (dialogName == 'flash') {
          var urlObj = 'src';
        } else if (dialogName == 'files' || dialogName == 'link') {
          var urlObj = 'url';
        } else {
          return;
        }
        dialog.setValueOf(dialog._.currentTabId, urlObj, url);
        elfNode.dialog('close');
        elfInsrance.disable();
      },
      uiOptions: {
        toolbar : [
          // toolbar configuration
          //['back', 'forward'],
          //['reload'],
          //['home', 'up'],
          [/*'mkdir', 'mkfile', */'upload'],
          ['open'],
          //['info'],
          //['quicklook'],
          //['copy', 'cut', 'paste'],
          ['rm'],
          //['duplicate', 'rename', 'edit'],
          //['extract', 'archive'],
          //['search'],
          //['view'],
          //['help']
        ]
      },

      contextmenu : {
          files  : [
              'getfile', '|','open', '|', 'copy', 'cut', 'paste', '|',
              'rm'
          ]
      }
    };




adminFileUploader.getShowImgSize = function(url, callback) {
  var ret = {};
  $('<img/>').attr('src', url).on('load', function() {
    var w = this.naturalWidth,
      h = this.naturalHeight,
      s = 400;
    if (w > s || h > s) {
      if (w > h) {
        h = h * (s / w);
        w = s;
      } else {
        w = w * (s / h);
        h = s;
      }
    }
    callback({width: w, height: h});
  });
};





adminFileUploader.iniciaUploader = function() {

    CKEDITOR.on('dialogDefinition', function (event) {
      //cogumelo.log(event);
      if(event.data.name === 'image') {
        adminFileUploader.dialogDefinition(event);
      }

    });

    CKEDITOR.on('instanceReady', function(e) {
      adminFileUploader.instanceReady(e);

    });


};

function hideElFinderToolbars() {
  $('.ui-corner-bottom.elfinder-statusbar, .elfinder-navbar').css('display', 'none');
  $('.ui-dialog .ui-dialog-content').css('padding',0);
}


adminFileUploader.instanceReady = function(e) {
  var cke = e.editor;

  cke.on('fileUploadRequest', function(e){
    var fileLoader = e.data.fileLoader,
      formData = new FormData(),
      xhr = fileLoader.xhr;
    xhr.open('POST', fileLoader.uploadUrl, true );
    formData.append('cmd', 'upload' );
    //formData.append('target', elfDirHashMap.image);
    formData.append('upload[]', fileLoader.file, fileLoader.fileName );
    fileLoader.xhr.send( formData );
  });

  cke.on('fileUploadResponse', function(e){
    elfInsrance.exec('reload');
    e.stop();
    var data = e.data,
      res = JSON.parse(data.fileLoader.xhr.responseText);
    if (!res.added || res.added.length < 1) {
      data.message = 'Can not upload.';
      e.cancel();
    } else {
      var file	 = res.added[0];
      if (file.url && file.url != '1') {
        data.url = file.url;
      } else {
        data.url = elfInsrance.options.url + ((elfInsrance.options.url.indexOf('?') === -1)? '?' : '&') + 'cmd=file&target=' + file.hash;
      }
    }
  });
};


adminFileUploader.dialogDefinition = function(event) {

  var editor = event.editor,
    dialogDefinition = event.data.definition,
    tabCount = dialogDefinition.contents.length,
    browseButton, uploadButton, submitButton, inputId;

  for (var i = 0; i < tabCount; i++) {
    browseButton = dialogDefinition.contents[i].get('browse');
    uploadButton = dialogDefinition.contents[i].get('upload');
    submitButton = dialogDefinition.contents[i].get('uploadButton');

    if (browseButton !== null) {
      browseButton.hidden = false;
      browseButton.onClick = function (dialog, i) {
        dialogName = CKEDITOR.dialog.getCurrent()._.name;
        if (elfNode) {
          if (elfDirHashMap[dialogName] && elfDirHashMap[dialogName] != elfInsrance.cwd().hash) {
            elfInsrance.request({
              data	 : {cmd	 : 'open', target : elfDirHashMap[dialogName]},
              notify : {type : 'open', cnt : 1, hideCnt : true},
              syncOnFail : true
            });
          }
          elfNode.dialog('open');
        }
      };
    }

    if (uploadButton !== null && submitButton !== null) {
      uploadButton.hidden = false;
      submitButton.hidden = false;
      uploadButton.onChange = function() {
        inputId = this.domId;
      };
      submitButton.onClick = function(e) {
        dialogName = CKEDITOR.dialog.getCurrent()._.name;
        var target = elfDirHashMap[dialogName]? elfDirHashMap[dialogName] : elfDirHashMap['fb'],
          name = $('#'+inputId),
          input = name.find('iframe').contents().find('form').find('input:file'),
          error = function(err) {
            alert(elfInsrance.i18n(err).replace('<br>', '\n'));
          };

        if (input.val()) {
          var fd = new FormData();
          fd.append('cmd', 'upload');
          fd.append('overwrite', 0);
          fd.append('target', target);
          $.each(customData, function(key, val) {
            fd.append(key, val);
          });
          fd.append('upload[]', input[0].files[0]);
          $.ajax({
            url: editor.config.filebrowserUploadUrl,
            type: "POST",
            data: fd,
            processData: false,
            contentType: false,
            dataType: 'json'
          })
          .done(function( data ) {
            if (data.added && data.added[0]) {
              var url = data.added[0].url;
              var dialog = CKEDITOR.dialog.getCurrent();
              if (dialogName == 'image') {
                var urlObj = 'txtUrl';
              } else if (dialogName == 'flash') {
                var urlObj = 'src';
              } else if (dialogName == 'files' || dialogName == 'link') {
                var urlObj = 'url';
              } else {
                return;
              }
              dialog.selectPage('info');
              dialog.setValueOf('info', urlObj, url);
              if (dialogName == 'image') {
                adminFileUploader.getShowImgSize(url, function(size) {
                  dialog.setValueOf('info', 'txtWidth', size.width);
                  dialog.setValueOf('info', 'txtHeight', size.height);
                  dialog.preview.$.style.width = size.width+'px';
                  dialog.preview.$.style.height = size.height+'px';
                });
              }
            } else {
              error(data.error || data.warning || 'errUploadFile');
            }
          })
          .fail(function() {
            error('errUploadFile');
          })
          .always(function() {
            input.val('');
          });
        }
        return false;
      };
    }

  }

};
