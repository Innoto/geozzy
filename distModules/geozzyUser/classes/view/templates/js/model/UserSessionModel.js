var geozzy = geozzy || {};
if(!geozzy.userSessionComponents) geozzy.userSessionComponents={};

geozzy.userSessionComponents.UserSessionModel = Backbone.Model.extend({
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
