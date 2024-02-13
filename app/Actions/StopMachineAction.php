<?php

namespace App\Actions;

use TCG\Voyager\Actions\AbstractAction;

class StopMachineAction extends AbstractAction
{
    public function getTitle()
    {
        // Action title which display in button based on current status
        return 'Stop';
    }

    public function getIcon()
    {
        // Action icon which display in left of button based on current status
        return 'voyager-pause';
    }

    public function getAttributes()
    {
        // Action button class
        return [
            'class' => 'btn btn-sm btn-danger pull-left ml',
        ];
    }

    public function shouldActionDisplayOnDataType()
    {
        // show or hide the action button, in this case will show for bot-profiles model
        return $this->dataType->slug == 'bot-profiles';
    }

    public function getDefaultRoute()
    {
        // URL for action button when click
        return route('socket.stop', array("id"=>$this->data->{$this->data->getKeyName()}));
    }
}