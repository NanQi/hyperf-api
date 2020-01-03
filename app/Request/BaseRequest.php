<?php

declare(strict_types=1);

namespace App\Request;

use App\Helper\ResponseFormatTrait;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Validation\Request\FormRequest;

abstract class BaseRequest extends FormRequest
{
    use ResponseFormatTrait;
    /**
     * @Inject
     * @var RequestInterface
     */
    protected $request;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [ ];
    }

}
