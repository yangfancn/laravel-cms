<?php

namespace App\Services\Form\Elements;

use App\Services\Form\Elements\Traits\Counter;

/**
 * Editor class represents a rich text editor form element.
 * Its basis on CKEditor 5, which allows users to create and edit rich text content.
 * @package App\Services\Form\Elements
 */
class Editor extends Element
{
    use Counter;

    protected string $field = 'ckEditor';

    protected ?string $minHeight;

    protected array $toolbarItems = [
        'undo',
        'redo',
        '|',
        'heading',
        '|',
        'showBlocks',
        '|',
        'alignment',
        'blockQuote',
        'bulletedList',
        'numberedList',
        'todoList',
        'link',
        'horizontalLine',
        '|',
        'bold',
        'italic',
        'fontColor',
        'fontBackgroundColor',
        'fontFamily',
        'fontSize',
        'underline',
        'strikethrough',
        'subscript',
        'superscript',
        '|',
        'outdent',
        'indent',
        '|',
        'imageUpload',
        'insertTable',
        'mediaEmbed',
        '|',
        'code',
        'codeBlock',
        'sourceEditing',
    ];

    /**
     * Set the toolbar items(plugins) for the editor.
     * @param  array  $items  split:"|", options: "undo","redo","heading","showBlocks","alignment","blockQuote","bulletedList","numberedList","todoList","link","horizontalLine","bold","italic","fontColor","fontBackgroundColor","fontFamily","fontSize","underline","strikethrough","subscript","superscript","outdent","indent","imageUpload","insertTable","mediaEmbed","code","codeBlock","sourceEditing"
     * @return $this
     */
    public function toolbar(array $items): self
    {
        $this->toolbarItems = $items;

        return $this;
    }

    /**
     * Set the minimum height for the editor.
     * @param string $minHeight 
     * @return \App\Services\Form\Elements\Editor 
     */
    public function minHeight(string $minHeight): self
    {
        $this->minHeight = $minHeight;

        return $this;
    }
}
