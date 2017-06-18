<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 18/06/17
 * Time: 01:47 PM
 */

namespace Ventamatic\Core\Branch;


use Illuminate\Http\Request;
use Ventamatic\Core\ImageResizeService;

class BranchService
{
    /**
     * @var ImageResizeService
     */
    private $image;

    /**
     * BranchService constructor.
     */
    public function __construct(ImageResizeService $image)
    {
        $this->image = $image;
    }

    public function update(Branch $branch, Request $request)
    {
        $branch->fill($request->all());
        if ( $imageBase64 = $request->get('image_base64')) {
            $branch->image_hash = $this->image->saveAndResizeImagesFromBase64($imageBase64, 'branch');
        }
        $branch->save();
    }

}