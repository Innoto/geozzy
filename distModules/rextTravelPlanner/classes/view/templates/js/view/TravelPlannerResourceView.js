var geozzy = geozzy || {};
if(!geozzy.travelPlannerComponents) geozzy.travelPlannerComponents={};

geozzy.travelPlannerComponents.TravelPlannerResourceView = Backbone.View.extend({
  el: "#resourceTpModal",
  mode: 'add', //Add or Edit
  resourceTemplate : false,
  modalTemplate : false,
  parentTp : false,
  idResource : false,
  planDays : false,
  dataRes : false,

  events: {
    'click .selectorDays li': 'selectorDays',
    'click .cancelAdd' : 'closeModalResource',
    'click .acceptAdd' : 'addToPlan',
    'click .cancelEdit' : 'closeModalResource',
    'click .acceptEdit' : 'editToPlan'
  },

  initialize: function( parentTp, idResource, dataRes, mode ) {
    var that = this;

    that.delegateEvents();
    that.parentTp = parentTp;
    that.idResource = idResource;

    var checkin =  that.parentTp.momentDate( that.parentTp.tpData.get('checkin') );
    var checkout = that.parentTp.momentDate( that.parentTp.tpData.get('checkout') );

    that.planDays = 1 + checkout.diff( checkin, 'days');

    that.mode = ( mode == 'edit' || mode == 'add' ) ? mode : that.mode;
    that.dataRes = (dataRes) ? dataRes : false;

    that.modalTemplate = _.template( geozzy.travelPlannerComponents.modalMdTemplate );
    that.render();
  },

  render: function() {
    var that = this;

    $('body').append( that.modalTemplate({ 'modalId': 'resourceTpModal', 'modalTitle': __('Add to Plan') }) );
    that.el = '#resourceTpModal';
    that.$el = $(that.el);

    item = that.parentTp.resources.get(that.idResource).toJSON();

    if(that.mode === 'edit'){
      //Edit
      that.dataRes.minutes = (that.dataRes.time % 60);
      that.dataRes.hours = ((that.dataRes.time - that.dataRes.minutes )/ 60);
      that.resourceTemplate = _.template( $('#resourceTpEditModalTemplate').html() );
      that.$('.modal-body').html( that.resourceTemplate({ resource: item, data: that.dataRes }) );
    }else{
      //Add
      var checkin = that.parentTp.momentDate(that.parentTp.tpData.get('checkin'));
      var dates = [];
      var selectedDays = that.parentTp.travelPlannerPlanView.resourceInPlan(that.idResource);

      for (i = 0; i < that.planDays; i++) {
        dates[i] = {
          id: i,
          date: checkin.format('LL'),
          dayName: checkin.format('dddd'),
          day: checkin.format('DD'),
          month: checkin.format('MMM'),
          inPlan: false
        };

        if($.inArray(i, selectedDays) !== -1){
          dates[i].inPlan = true;
        }
        checkin.add(1, 'days');
      }


      if (typeof(item.rextmodels.RExtVisitDataModel) != "undefined" && item.rextmodels.RExtVisitDataModel.duration > 0 ){
        item.defaultDurationH = Math.floor( item.rextmodels.RExtVisitDataModel.duration / 60 );
        item.defaultDurationM = item.rextmodels.RExtVisitDataModel.duration % 60;
      }

      that.resourceTemplate = _.template( $('#resourceTpModalTemplate').html() );
      that.$('.modal-body').html( that.resourceTemplate({ resource: item, dates: dates }) );
    }

    that.$el.modal({
      'show' : true,
      'keyboard': false,
      'backdrop' : 'static'
    });
    that.$el.on('hidden.bs.modal', function (e) {
      e.target.remove();
    });
    $(document).on('hidden.bs.modal', '.modal', function () {
      $('.modal:visible').length && $(document.body).addClass('modal-open');
    });

    that.$('.resourceTp form').validate({
      lang: cogumelo.publicConf.C_LANG,
      rules: {
        'hlong-hour':{
          'min': 0 ,
          'max':23,
          'digits': true,
          //required: true
          required: function(){
            if($('.hlong-minutes').val()==""){
              return true;
            }
            else{
              return false;
            }
          }
        },
        'hlong-minutes':{
          'min':0,
          'max':59,
          'digits': true,
          //required: true
          required: function(){
            if($('.hlong-hour').val()==""){
              return true;
            }
            else{
              return false;
            }
          }
        }
      },
      messages: {
        'hlong-hour': __('Introduce entre 0 y 23 horas'),
        'hlong-minutes': __('Introduce entre 0 y 59 minutos')
      }
    });

    return that;
  },

  closeModalResource: function(){
    var that = this;
    that.$el.modal('hide');
  },

  selectorDays: function (e){
    var day = $(e.target).closest('.selectorDays li');
    if(day.hasClass('active')){
      day.removeClass('active');
    }else{
      day.addClass('active');
    }
  },

  addToPlan: function(e){
    var that = this;
    if( that.$('.resourceTp form').valid()){
      var daysSelectorActive = $('.selectorDays li.active');
      var daysActive = [];
      daysSelectorActive.each(function( index ) {
        daysActive.push($( this ).attr('data-day'));
      });

      var hours = that.$('.resourceTp .hlong-hour').val();
      hours = (hours) ? hours : 0;
      var minutes = that.$('.resourceTp .hlong-minutes').val();
      minutes = (minutes) ? minutes : 0;
      var visitTime = 0;
      visitTime = (parseInt(hours) * 60) + parseInt(minutes);

      that.parentTp.travelPlannerPlanView.addResourcesPlan(that.idResource, daysActive, visitTime);
      that.closeModalResource();
    }
  },
  editToPlan: function(e){
    var that = this;
    if( that.$('.resourceTp form').valid()){
      that.dataRes.hours = that.$('.resourceTp .hlong-hour').val();
      that.dataRes.minutes = that.$('.resourceTp .hlong-minutes').val();

      var visitTime = 0;
      visitTime = (parseInt(that.dataRes.hours) * 60) + parseInt(that.dataRes.minutes);
      that.dataRes.time = visitTime;

      that.parentTp.travelPlannerPlanView.editResourcesPlan(that.dataRes);
      that.closeModalResource();
    }
  }
});
