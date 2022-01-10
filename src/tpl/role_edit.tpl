{BBOXCENTER}
{BPANEL|paneltitle::Edit role}

{BFORMSTART|admin_role.php}
{HIDDEN|action|editdo}
{HIDDEN|id|{%id}}
{HIDDEN|form_token|{%form_token}}

  {BTEXT|name|{*name}|texttitle::Role Name|size::3}
  {BFORMGRP|Privileges|size::4}{%sel_privileges}{/BFORMGRP}
  {BFORMGRP|Subroles|size::4}{%sel_roles}{/BFORMGRP}

{BFORMSUBMIT|class::center-block}
{BFORMEND}
{/BPANEL}
{/BBOXCENTER}
