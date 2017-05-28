<?php

namespace Ngmy\Webloyer\Webloyer\Application\Deployment;

use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Deployment;
use Robbo\Presenter\Presenter;

class DeploymentPresenter extends Presenter
{
    private $converter;

    public function __construct(Deployment $deployment, $converter)
    {
        parent::__construct($deployment);

        $this->converter = $converter;
    }

    public function statusIcon()
    {
        if ($this->status()->isSuccess()) {
            return '<span class="glyphicon glyphicon-ok-circle green" aria-hidden="true"></span>';
        }
        if ($this->status()->isFailure()) {
            return '<span class="glyphicon glyphicon-ban-circle red" aria-hidden="true"></span>';
        }
        if ($this->status()->isRunning()) {
            return '<span></span>';
        }
    }

    public function messageHtml()
    {
        $html = $this->converter->convert($this->message());

        return $html;
    }

    public function messageText()
    {
        $html = $this->messageHtml();
        $text = htmlspecialchars_decode(strip_tags($html));

        return $text;
    }
}
