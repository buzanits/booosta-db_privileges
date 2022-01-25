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
  public $translator_dir = 'lib/modules/db_privileges';

  protected $fields = 'name,edit,delete';
  protected $header = 'Privilege';
  protected $use_datatable = true;
  #protected $ui_modal_cancelpage = 'admin_privilege';


  protected function before_add_($data, $obj)
  {
    if(substr($data['name'], 0, 1) == '*'):
      $tablename = substr($data['name'], 1);
      $obj->set('name', "view $tablename");

      $obj1 = $this->makeDataobject('privilege');
      foreach(['create','edit','delete'] as $action):
        $obj1->set('name', "$action $tablename");
        $obj1->insert();
      endforeach;
    endif;
  }
}

$app = new App('privilege');
$app->auth_user();
$app();
