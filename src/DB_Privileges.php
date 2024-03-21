<?php
namespace booosta\db_privileges;
use \booosta\Framework as b;
b::init_module('db_privileges');

class DB_Privileges extends \booosta\privileges\Privileges
{
  use moduletrait_db_privileges;

  protected $privilege_table = 'privilege';
  protected $user_privilege_table = 'user_privilege';
  protected $role_privilege_table = 'role_privilege';
  protected $role_table = 'role';
  protected $role_role_table = 'role_role';
  protected $user_role_table = 'user_role';

  public function get_subroles($role_id)
  {
    $this->init_db();
    return $this->DB->query_value_set("select subrole from `$this->role_role_table` where superrole=?", $role_id);
  }

  public function get_all_role_privileges($role)
  {
    $this->init_db();
    if(!$this->check_role_loops($role)) return 'ERROR: roles build loops!';

    $privileges = $this->DB->query_value_set("select privilege from `$this->role_privilege_table` where role=?", $role);
    $subroles = $this->DB->query_value_set("select subrole from `$this->role_role_table` where superrole=?", $role);

    foreach($subroles as $subrole):
      $subroleprivs = $this->get_all_role_privileges($subrole);
      if(strstr(print_r($subroleprivs, true), 'ERROR')) return $subroleprivs;

      $privileges = array_merge($privileges, $subroleprivs);
    endforeach;

    return array_unique($privileges);
  }

  public function get_privilege_id($privilege_name)
  {
    $this->init_db();
    return $this->DB->query_value("select id from `$this->privilege_table` where name=?", $privilege_name);
  }

  public function add_user_privilege($user_id, $privilege)
  {
    $this->init_db();
    $privid = $this->DB->query_value("select id from `$this->privilege_table` where name=?", $privilege);
    $this->DB->query("insert into `$this->user_privilege_table` (user, privilege) values (?, ?)", [$user_id, $privid]);
  }

  public function get_user_privileges($user_id)
  {
    $this->init_db();
    return $this->DB->query_index_array("select id, name from `$this->privilege_table` where id in (select privilege from `$this->user_privilege_table` where user=?)", $user_id);
  }

  public function get_user_roles($user_id)
  {
    $this->init_db();
    return $this->DB->query_index_array("select id, name from `$this->role_table` where id in (select role from `$this->user_role_table` where user=?)", $user_id);
  }

  public function set_user_privileges($user_id, $privs)
  {
    $this->init_db();
    $this->DB->query("delete from `$this->user_privilege_table` where user=?", $user_id);
    foreach($privs as $priv)
      if(is_numeric($priv)) $this->DB->query("insert into `$this->user_privilege_table` (user, privilege) values (?, ?)", [$user_id, $priv]);
  }

  public function set_user_roles($user_id, $roles)
  {
    $this->init_db();
    $this->DB->query("delete from `$this->user_role_table` where user=?", $user_id);
    foreach($roles as $role)
      if(is_numeric($role)) $this->DB->query("insert into `$this->user_role_table` (user, role) values (?, ?)", [$user_id, $role]);
  }
}
