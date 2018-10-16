<?php

use LaravelEnso\StructureManager\app\Classes\StructureMigration;

class CreateStructureForMembers extends StructureMigration
{
    protected $permissionGroup = [
        'name' => 'administration.members', 'description' => 'Members permissions group',
    ];

    protected $permissions = [
        ['name' => 'administration.members.index', 'description' => 'Show members', 'type' => 0, 'is_default' => false],
        ['name' => 'administration.members.store', 'description' => 'Store newly created member', 'type' => 1, 'is_default' => false],
        ['name' => 'administration.members.destroy', 'description' => 'Delete member', 'type' => 1, 'is_default' => false],
        ['name' => 'administration.members.options', 'description' => 'Get options for select', 'type' => 0, 'is_default' => false],
    ];

    protected $menu = [
        'name' => 'Members', 'icon' => 'users-cog', 'link' => 'administration.members.index', 'order_index' => 300, 'has_children' => false,
    ];

    protected $parentMenu = 'Administration';
}
