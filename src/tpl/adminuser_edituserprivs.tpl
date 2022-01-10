<h1>Edit privileges</h1>

{FORMSTART admin_user.php}
{HIDDEN action editprivs_do}
{HIDDEN id {%id}}
{HIDDEN form_token {%form_token}}

{%box}

<h1>Edit roles</h1>

{%rolebox}

{FORMSUBMIT}
{FORMEND}
