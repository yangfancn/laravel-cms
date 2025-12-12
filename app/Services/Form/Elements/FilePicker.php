<?php

namespace App\Services\Form\Elements;

use App\Services\Form\Elements\Traits\Clearable;
use App\Services\Form\Elements\Traits\HasAffixes;
use App\Services\Form\Elements\Traits\Multiple;
use App\Services\Form\Elements\Traits\Styles;
use App\Services\Form\Elements\Traits\UploadFiles;
use App\Services\Form\Elements\Traits\UseChips;

/**
 * FilePicker class represents a file picker form element.
 */
class FilePicker extends Element
{
    use Clearable, HasAffixes, Multiple, Styles, UploadFiles, UseChips;

    protected string $field = 'filePicker';

    /**
     * Set the prepend icon for the file picker.
     */
    public function prependIcon(string $icon = 'cloud_upload'): self
    {
        $this->prependIcon = $icon;

        return $this;
    }
}
