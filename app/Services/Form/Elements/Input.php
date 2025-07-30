<?php

namespace App\Services\Form\Elements;

use App\Services\Form\Elements\Traits\Clearable;
use App\Services\Form\Elements\Traits\Counter;
use App\Services\Form\Elements\Traits\HasAffixes;
use App\Services\Form\Elements\Traits\Styles;

/**
 * Input class represents a form input element.
 * It provides methods to configure the input type, autocomplete, mask, and other properties.
 * @package App\Services\Form\Elements
 */
class Input extends Element
{
    use Clearable, Counter, HasAffixes, Styles;

    protected string $field = 'input';

    protected string $type = 'text';

    protected ?bool $showPassword = null;

    protected null|true|string $autocomplete = null;

    /**
     * @var string|null ####-##-##
     *                  #    Numeric
     *                  S    Letter, a to z, case-insensitive
     *                  N    Alphanumeric, case-insensitive for letters
     *                  A    Letter, transformed to uppercase
     *                  a    Letter, transformed to lowercase
     *                  X    Alphanumeric, transformed to uppercase for letters
     *                  x    Alphanumeric, transformed to lowercase for letters2
     */
    protected ?string $mask = null;

    protected bool $fillMask = false;

    /**
     * Set the input type to email.
     * @return \App\Services\Form\Elements\Input 
     */
    public function email(): self
    {
        $this->type = 'email';

        return $this;
    }

    /**
     * Set the input type to number.
     * @return \App\Services\Form\Elements\Input 
     */
    public function number(): self
    {
        $this->type = 'number';

        return $this;
    }

    /**
     * Set the input type to password.
     * @return \App\Services\Form\Elements\Input 
     */
    public function password(): self
    {
        $this->type = 'password';
        $this->showPassword = false;

        return $this;
    }

    /**
     * Set the input type to textarea.
     * @return \App\Services\Form\Elements\Input 
     */
    public function textarea(): self
    {
        $this->type = 'textarea';

        return $this;
    }

    //    public function hidden(): self
    //    {
    //        $this->type = 'hidden';
    //        return $this;
    //    }

    /**
     * allow auto completion of the input field.
     * @param string|true $auto 
     * @return \App\Services\Form\Elements\Input 
     */
    public function autocomplete(string|true $auto = true): self
    {
        $this->autocomplete = $auto === true ? $this->name : $auto;

        return $this;
    }

    /**
     * set input mast
     * @param string $mask example: '####-##-##', 'SNNN-SSSS', 'AaaXxx'
     * @return \App\Services\Form\Elements\Input 
     */
    public function mask(string $mask): self
    {
        $this->mask = $mask;

        return $this;
    }

    /**
     * Set whether to fill the mask automatically.
     * @param bool $fill 
     * @return \App\Services\Form\Elements\Input 
     */
    public function fillMask(bool $fill = true): self
    {
        $this->fillMask = $fill;

        return $this;
    }
}
