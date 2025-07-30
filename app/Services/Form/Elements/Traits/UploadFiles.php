<?php

namespace App\Services\Form\Elements\Traits;

/**
 * Trait UploadFiles
 * This trait provides methods to configure file upload settings such as accepted file types,
 * maximum file size, total size, and number of files.
 *
 * @package App\Services\Form\Elements\Traits
 */
trait UploadFiles
{
    protected string $accept = '*';

    protected ?int $maxFileSize = null;

    protected ?int $maxTotalSize = null;

    protected ?int $maxFiles = null;

    /**
     * Set the accepted file types for upload.
     * @param string $accept example: 'image/*', '.pdf', '.docx', 'video/*'
     * @return \App\Services\Form\Elements\Traits\UploadFiles 
     */
    public function accept(string $accept): self
    {
        $this->accept = $accept;

        return $this;
    }

    /**
     * Set the maximum file size for each uploaded file.
     * The size is specified in bytes.
     * @param int $size 
     * @return \App\Services\Form\Elements\Traits\UploadFiles 
     */
    public function maxFileSize(int $size): self
    {
        $this->maxFileSize = $size;

        return $this;
    }

    /**
     * Set the maximum total size for all uploaded files.
     * The size is specified in bytes.
     * @param int $size 
     * @return \App\Services\Form\Elements\Traits\UploadFiles 
     */
    public function maxTotalSize(int $size): self
    {
        $this->maxTotalSize = $size;

        return $this;
    }

    /**
     * Set the maximum number of files that can be uploaded.
     * @param int $length 
     * @return \App\Services\Form\Elements\Traits\UploadFiles 
     */
    public function maxFiles(int $length): self
    {
        $this->maxFiles = $length;

        return $this;
    }
}
