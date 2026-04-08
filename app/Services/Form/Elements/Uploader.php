<?php

namespace App\Services\Form\Elements;

/**
 * Uploader class represents a file uploader form element.
 */
class Uploader extends Element
{
    protected string $field = 'uploader';

    protected bool $disabled = false;

    protected bool $allowMultiple = false;

    protected int $maxFiles = 1;

    protected bool $allowReorder = false;

    protected string $acceptedFileTypes = 'image/*';

    protected bool $cropper = false;

    protected float|int $aspectRatio;

    /**
     * Constructor for the Uploader class.
     */
    public function disable(bool $disable = true): self
    {
        $this->disabled = $disable;

        return $this;
    }

    /**
     * Set the maximum number of files that can be uploaded.
     */
    public function maxFiles(int $maxFiles = 1): self
    {
        $this->maxFiles = $maxFiles;
        $maxFiles > 1 && $this->allowMultiple = true;

        return $this;
    }

    /**
     * Allow resrot the uploaded files.
     */
    public function allowReorder(): self
    {
        $this->allowReorder = true;

        return $this;
    }

    /**
     * Set the accepted file types for upload.
     *
     * @param  string  $accept  example: 'image/*', '.pdf, .docx', etc.
     */
    public function accept(string $accept): self
    {
        $this->acceptedFileTypes = $accept;

        return $this;
    }

    /**
     * cropper image before upload.
     *
     * @param  float|int|null  $aspectRatio  example: 1.0 for square, 16/9 for widescreen
     */
    public function cropper(float|int|null $aspectRatio = null): self
    {
        $this->cropper = true;
        $aspectRatio && $this->aspectRatio = $aspectRatio;

        return $this;
    }
}
