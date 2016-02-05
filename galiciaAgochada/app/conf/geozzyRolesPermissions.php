<?php

global $CGMLCONF;
/*
'permissions' => array(
  'category:list',
  'category:create',
  'category:edit',
  'category:delete',

  'resource:mylist',
  'resource:list',
  'resource:create',
  'resource:edit',
  'resource:delete',
  'resource:publish',

  'topic:list',
  'topic:assign',

  'starred:list',
  'starred:assign',

  'user:all',

  'admin:access',
  'admin:full'
)
*/

$CGMLCONF['user']['roles']['administrador'] = array(
  'name' => 'administrador',
  'description' => 'Role Administrador',
  'permissions' => array(
    'admin:full',
    'admin:access'
  )
);

$CGMLCONF['user']['roles']['gestor'] = array(
  'name' => 'gestor',
  'description' => 'Role Gestor',
  'permissions' => array(
    'category:list',
    'category:create',
    'category:edit',
    'category:delete',

    'resource:mylist',
    'resource:list',
    'resource:create',
    'resource:edit',
    'resource:delete',
    'resource:publish',

    'topic:list',
    'topic:assign',

    'starred:list',
    'starred:assign',

    'user:all',

    'admin:access'
  )
);
