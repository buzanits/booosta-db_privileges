<?php
namespace booosta\db_privileges;

include '../../chroot.php';
require_once __DIR__ . '/vendor/autoload.php';

use booosta\Framework as b;
b::load();

class App extends \booosta\usersystem\Webappadmin
{
  public $base_dir = '../../../';
  public $tpldir = 'lib/modules/db_privileges/';
  public $translator_dir = 'lib/modules/db_privileges';

  protected $fields = 'name,edit,delete';
  protected $header = 'Privilege';
  protected $use_datatable = true;


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
