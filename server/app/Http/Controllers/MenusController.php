<?php

namespace App\Http\Controllers;

use App\Models\Menu;

class MenusController extends Controller
{
    var $outputData = [];

    /**
     * GET: /menus
     * Get the menu category
     *
     * id: number (optional): if isset => not get the current id & its childrent
     * depth: number (optional): get the level of the tree
     *
     */
    public function index()
    {
        if(isset($this->data['depth']))
        {
            $menus = Menu::withDepth()->having('depth', '<=', $this->data['depth'])->get()->toTree();
        }
        else
        {
            $menus = Menu::orderBy('order')->get()->toTree();
        }

        $traverse = function ($menus, $prefix = '') use (&$traverse) {
            foreach ($menus as $menu) {
                //echo PHP_EOL.$prefix.''.$menu->name;

                if(isset($this->data['id']) && $this->data['id'] == $menu->id) continue; // Not get child of current node

                array_push($this->outputData,
                [
                    'id'=>$menu->id,
                    'parent_id'=>$menu->parent_id,
                    'name'=>$prefix.''.$menu->name,
                    'link'=>$menu->link,
                    'active'=>$menu->active,
                    'type'=>$menu->type,
                    'type_internal'=>$menu->type_internal
                ]);

                $traverse($menu->children, $prefix.'__');
            }
        };

        $traverse($menus);
        $this->output(['data'=>$this->outputData],200);
    }

    public function listMenus()
    {
        $menus = Menu::scopeGetInternalLink()->toTree();
        $this->output(['data'=>$menus],200);
    }

    public function getForm()
    {
        $menus = Menu::where([['id', $this->data['id']]])->first();

        if($menus)
        {
            $this->output(['data'=>$menus], 200);
        }
        else
        {
            $this->output([MESSAGE=>trans('Menu does not exist')], 404);
        }
    }

    public function saveForm()
    {
        if($this->data['id'])
        {
            $this->validate($this->request,
                [
                    'name' => 'required|unique:menus,name,'.$this->data['id'],
                ]);
        }
        else
        {
            $this->validate($this->request,
                [
                    'name' => 'required|unique:menus',
                ]);
        }

        $this->saveRecord('Menu');
    }

    public function delete()
    {
        $this->deleteRecord('Menu');
    }
    
    public function updateOrder() {
        $data = $this->data;
        foreach ($data as $menuId => $order) {
            $menu = Menu::where(['id' => $menuId])->first();
            if (!$menu) {
                continue;
            }
            $menu->order = $order;
            $menu->save();
        }
        $this->output(['message' => 'Menu has been updated'], 200);
    }
}
