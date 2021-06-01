<?php

namespace app\assets;

class RoleToAccessAsset extends AppAsset
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/settings/rta.css',
    ];
    public $js = [
        'js/role-access.js'
    ];
}