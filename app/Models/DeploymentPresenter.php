<?php
declare(strict_types=1);

namespace App\Models;

use Robbo\Presenter\Presenter;
use SensioLabs\AnsiConverter\AnsiToHtmlConverter;

/**
 * Class DeploymentPresenter
 * @package App\Models
 */
class DeploymentPresenter extends Presenter
{
    /**
     * @var AnsiToHtmlConverter
     */
    protected AnsiToHtmlConverter $converter;

    /**
     * DeploymentPresenter constructor.
     * @param mixed $object
     * @param AnsiToHtmlConverter $converter
     */
    public function __construct(
        $object,
        AnsiToHtmlConverter $converter
    )
    {
        parent::__construct($object);
        $this->converter = $converter;
    }

    /**
     * @return string
     */
    public function status()
    {
        if (!isset($this->status)) {
            return '<span></span>';
        } elseif ($this->status === 3) {
            return '<span class="glyphicon glyphicon glyphicon-play-circle yellow" aria-hidden="true"></span>';
        } elseif ($this->status === 0) {
            return '<span class="glyphicon glyphicon-ok-circle green" aria-hidden="true"></span>';
        } else {
            return '<span class="glyphicon glyphicon-ban-circle red" aria-hidden="true"></span>';
        }
    }

    /**
     * @return string
     */
    public function statusText()
    {
        switch (strval($this->status)) {
            case "0":
                $status = 'success';
                break;
            case "1":
                $status = 'failure';
                break;
            case "2":
                $status = 'canceled';
                break;
            case "3":
                $status = 'running';
                break;
            default:
                $status = 'pending';
                break;
        }
        return $status;
    }

    /**
     * @return mixed
     */
    public function message()
    {
        return $this->converter->convert($this->message);
    }

    /**
     * @return string
     */
    public function messageText()
    {
        $html = $this->message();
        return htmlspecialchars_decode(strip_tags($html));
    }
}
