<?php
namespace booosta\db_privileges;

\booosta\Framework::add_module_trait('webapp', 'db_privileges\webapp');
\booosta\Framework::add_module_trait('genericuser', 'db_privileges\genericuser');

trait Webapp
{
  protected $privileges_dir = 'vendor/booosta/db_privileges/';

  protected function autorun_db_privileges()
  {
    if($this->TPL === null) $this->TPL = [];
    $this->TPL['privileges_dir'] = $this->privileges_dir;
  }
}

trait Genericuser
{
  protected function get_privilege_id($name)
  {
    return $this->DB->query_value("select id from privilege where name=?", $name);
  }

  protected function get_role_id($name)
  {
    return $this->DB->query_value("select id from role where name=?", $name);
  }
}
