<?php
/*
'permissions' => array(
  'category:list',
  'category:create',
  'category:edit',
  'category:delete',

  'resource:mylist',
  'resource:create',
  'resource:edit',
  'resource:delete',
  'resource:publish',

  'topic:list',
  'topic:assign',

  'page:list',

  'setting:list',

  'starred:list',
  'starred:assign',

  'user:all',

  'admin:access',
  'admin:full',

  'filedata:privateAccess'
)
*/
cogumeloSetSetupValue( 'user:roles:administrador',
  array(
    'name' => 'administrador',
    'description' => 'Role Administrador',
    'permissions' => array(
      'admin:full',
      'admin:access',
      'filedata:privateAccess'
    )
  )
);

cogumeloSetSetupValue( 'user:roles:gestor',
  array(
    'name' => 'gestor',
    'description' => 'Role Gestor',
    'permissions' => array(
      'admin:access',
      'topic:list',
      'resource:edit',
      'filedata:privateAccess'
    )
  )
);

cogumeloSetSetupValue( 'user:roles:tribunalProfesores',
  array(
    'name' => 'Tribunal Profesores',
    'description' => 'Tribunal Profesores',
    'permissions' => array(
      'tribunal:access',
      'tribunal:profesores',
      'filedata:privateAccess'
    )
  )
);
