var geozzy = geozzy || {};
if(!geozzy.model) geozzy.model={};

geozzy.model.UserSessionModel = Backbone.Model.extend({
  defaults: {
    id: false,
    login: '',
    name: '',
    surname: '',
    email: '',
    active: '',
    timeLastLogin: 0,
    timeCreateUser: 0,
    avatar: false
  },
  urlRoot: 'api/core/usersession'
});
