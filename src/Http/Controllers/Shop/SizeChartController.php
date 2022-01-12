<?php

namespace Webkul\SizeChart\Http\Controllers\Shop;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Webkul\SizeChart\Repositories\SizeChartRepository;
use Webkul\SizeChart\Repositories\AssignTemplateRepository;
use Webkul\Attribute\Repositories\AttributeRepository;

class SizeChartController extends Controller
{
    use DispatchesJobs, ValidatesRequests;

    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $sizechartRepository;

    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $assignTemplateRepository;

    /**
     * AttributeRepository object
     *
     * @var \Webkul\Attribute\Repositories\AttributeRepository
     */
    protected $attributeRepository;

    /**
     * Create a new controller instance.
     * 
     * @param Webkul\SizeChart\Repositories\SizeChartRepository   $sizechartRepository
     * @param Webkul\SizeChart\Repositories\AssignTemplateRepository   $assignTemplateRepository
     * @param  \Webkul\Attribute\Repositories\AttributeRepository  $attributeRepository
     *
     * @return void
     */
    public function __construct(
        SizeChartRepository $sizechartRepository,
        AssignTemplateRepository $assignTemplateRepository,
        AttributeRepository $attributeRepository
    )
    {
        $this->_config = request('_config');

        $this->sizechartRepository = $sizechartRepository;

        $this->assignTemplateRepository = $assignTemplateRepository;

        $this->attributeRepository = $attributeRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function getSizeChart($productId)
    {
        
        $assignTemplate = $this->assignTemplateRepository->findOneWhere(['product_id' => $productId]);
       
        if ($assignTemplate) {
            return $this->sizechartRepository->findOrFail($assignTemplate->template_id);
        } else {
            return false;
        }
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function getOptions($id)
    {
        $temp =  [];

        $sizeChart = $this->sizechartRepository->findOrFail($id);

        $data = json_decode($sizeChart->size_chart);
    
        foreach ($data[0] as $key => $value) {
            if ($key == 'label') {
                $label = $value;
            } else {
                array_push($temp, $value);
            }
        }

        return $temp;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return string
     */
    public function  saveTemplate()
    {
        $sizeChartId = request('sizechart-id');

        $productId = request('product-id');

        $data = [
            'product_id' => $productId,
            'template_id' => $sizeChartId
        ];

        $check = $this->assignTemplateRepository->findOneWhere(['product_id' => $productId]);

        if (! $sizeChartId) {
            if ($check) {
                $this->assignTemplateRepository->delete($check->id);
            }
        } else {
            if ($check) {
                $this->assignTemplateRepository->update($data, $check->id);
            } else {
                $this->assignTemplateRepository->create($data);
            }
        }
        
        return  $response = [
                    'status'   => true,
                ];
    }
}
