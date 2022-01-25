<?php
namespace booosta\db_privileges;

require_once __DIR__ . '/../../../../vendor/autoload.php';

use booosta\Framework as b;
b::croot();
b::load();

class App extends \booosta\usersystem\Webappadmin
{
  public $base_dir = '/';
  public $subtpldir = 'vendor/booosta/db_privileges/exec/';
  public $translator_dir = 'vendor/booosta/db_privileges/exec';

  protected $fields = 'name,edit,delete';
  protected $header = 'Role';
  protected $use_datatable = true;
  #protected $ui_modal_cancelpage = 'admin_role.php';


  protected function after_action_edit()
  {
    $act_privs = $this->DB->query_value_set("select privilege from role_privilege where role=?", $this->id);
    $sel = $this->makeInstance('ui_select', 'privileges', $this->get_opts_from_table('privilege'), $act_privs);
    $sel->set_type('tags');
    $this->TPL['sel_privileges'] = $sel->get_html();
   
    $act_roles = $this->DB->query_value_set("select subrole from role_role where superrole=?", $this->id);
    $sel = $this->makeInstance('ui_select', 'roles', $this->get_opts_from_table('role'), $act_roles);
    $sel->set_type('tags');
    $this->TPL['sel_roles'] = $sel->get_html();
  }

  protected function after_action_editdo()
  {
    $privs = $this->VAR['privileges'];
    $this->DB->query("delete from role_privilege where role=?", $this->id);
    foreach($privs as $priv)
      if(is_numeric($priv)) $this->DB->query("insert into role_privilege (role, privilege) values (?, ?)", [$this->id, $priv]);

    $roles = $this->VAR['roles'];
    $this->DB->query("delete from role_role where superrole=?", $this->id);
    foreach($roles as $role)
      if(is_numeric($role)) $this->DB->query("insert into role_role (superrole, subrole) values (?, ?)", [$this->id, $role]);
  }
}

$app = new App('role');
$app->auth_user();
$app();
