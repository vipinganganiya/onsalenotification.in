<?php

namespace App\Actions;

use TCG\Voyager\Actions\AbstractAction;

class StartMachineAction extends AbstractAction
{
    public function getTitle()
    {
        // Action title which display in button based on current status
        return 'Start';
    }

    public function getIcon()
    {
        // Action icon which display in left of button based on current status
        return 'voyager-power';
    }

    public function getAttributes()
    {
        // Action button class
        return [
            'class' => 'btn btn-sm btn-success pull-left view',
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
        return route('socket.start', array("id"=>$this->data->{$this->data->getKeyName()}));
    }
}