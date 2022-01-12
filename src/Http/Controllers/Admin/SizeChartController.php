<?php

namespace Webkul\SizeChart\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Webkul\SizeChart\Repositories\SizeChartRepository;
use Webkul\SizeChart\Repositories\AssignTemplateRepository;
use Webkul\Attribute\Repositories\AttributeRepository;

class SizeChartController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
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
        $this->middleware('admin');

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
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $type = request('type');

        $attributes = $this->attributeRepository->findWhere(['is_filterable' =>  1]);

        return view($this->_config['view'],compact('type', 'attributes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function getAll($type)
    {
        $templates = [];

        if ($type == "simple" || $type == "virtual") {
            
            $templates = $this->sizechartRepository->findWhere(['template_type' =>  'simple']);
        } else if ($type == "configurable") {
            $templates = $this->sizechartRepository->findWhere(['template_type' =>  'configurable']);
        }

        return $templates;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function savedTemp($productId)
    {
        $check = $this->assignTemplateRepository->findOneWhere(['product_id' => $productId]);

        if ($check) {
            $sizeChart = $this->sizechartRepository->findOrFail($check->template_id);
        }
        
        if ($check) {
            return $sizeChart;
        } else {
            return 0;
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return string
     */
    public function  attributeOptions()
    {
        $availableOptions = [];

        $id = request('attribute-id');

        $attribute = $this->attributeRepository->findOrFail($id);

        if (count($attribute->options)) {
            foreach ($attribute->options as $key => $option) {
                array_push($availableOptions, $option->admin_name);
            }

            $availableOptions = implode(',', $availableOptions);
    
            return  $response = [
                       'status'          => true,
                       'customOptionValues' => $availableOptions
                    ];
        } else {
            return  $response = [
                'status'          => false,
             ];
        }        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function store(Request $request)
    {
        $imagePath = '';
        
        $this->validate(request(), [
            'template_type' => 'required',
            'template_name' => 'required',
            'formname'      => 'required',
            'template_code' => ['required', 'unique:size_charts,template_code'],
            'image.*'    => 'required|mimes:jpeg,bmp,png,jpg',
        ]);
    
        $sizeChart = json_encode(request('formname'));
        
        if (count(request('formname')) < 2) {
            session()->flash('error', trans('sizechart::app.sizechart.response.atleast-one-row', ['name' => request('template_name')]));
            
            return redirect()->back();       
        }

        if (request('image')) {
            foreach (request('image') as $imageId => $image) {
                $file = 'image.' . $imageId;
                $dir = 'template';

                if (request()->hasFile($file)) {
                   $imagePath = request()->file($file)->store($dir);
                }
            }
        }
        
        $request->request->remove('formname');
        $request->request->add(['size_chart' => $sizeChart]);
        $request->request->add(['image_path' => $imagePath]);

        $this->sizechartRepository->create(request()->all());

        session()->flash('success', trans('sizechart::app.sizechart.response.create-success', ['name' => request('template_name')]));

        return redirect()->route('sizechart.admin.index');        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $label = '';

        $temp =[];

        $counter = 0;

        $customOptions = '';

        $sizeChart = $this->sizechartRepository->findOrFail($id);

        $attributes = $this->attributeRepository->findWhere(['is_filterable' =>  1]);
        
        $data = json_decode($sizeChart->size_chart);

        if ($data) {
            foreach ($data[0] as $key => $value) {
                if ($key == 'label') {
                    $label = $value;
                } else {
                    array_push($temp, $value);
                }
            }

            $customOptions = implode(',', $temp);

            array_splice($data, 0, 1);

            foreach ($data as $key => $value) {
                $data[$key] = (array)$data[$key];
                $data[$key]['row'] = ++$counter;
            }

            $addRows = $data;
        }
        else {
            $addRows = [];
        }

        

        return view($this->_config['view'],compact('sizeChart', 'customOptions', 'label', 'addRows', 'attributes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = request('template_id');
        $imagePath = '';
        $sizeChart = json_encode(request('formname'));

	if (count(request('formname')) < 2) {
            session()->flash('error', trans('sizechart::app.sizechart.response.atleast-one-row', ['name' => request('template_name')]));
            
            return redirect()->back();       
        }

        if (request('images')) {
            foreach (request('images') as $imageId => $image) {
                $file = 'images.' . $imageId;
                $dir = 'template';
                
                if (request()->hasFile($file)) {
                   $imagePath = request()->file($file)->store($dir);
                   $request->request->add(['image_path' => $imagePath]);
                }
            }
        }
        
        $request->request->remove('formname');
        $request->request->add(['size_chart' => $sizeChart]);

        $this->sizechartRepository->update(request()->all(), $id);

        session()->flash('success', trans('admin::app.response.update-success', ['name' => request('template_name')]));

        return redirect()->route('sizechart.admin.index'); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sizeChart = $this->sizechartRepository->findOrFail($id);

        try {
            $this->sizechartRepository->delete($id);

            session()->flash('success', trans('admin::app.response.delete-success', ['name' => 'Template']));

            return response()->json(['message' => true], 200);
        } catch (Exception $e) {
            report($e);

            session()->flash('error', trans('admin::app.response.delete-failed', ['name' => 'Template']));
        }

        return response()->json(['message' => false], 400);
    }

    /**
     * Mass Delete the products
     *
     * @return \Illuminate\Http\Response
     */
    public function massDestroy()
    {
        $templateIds = explode(',', request()->input('indexes'));

        foreach ($templateIds as $templateId) {
            $template = $this->sizechartRepository->find($templateId);

            if (isset($template)) {
                $this->sizechartRepository->delete($templateId);
            }
        }

        session()->flash('success', trans('admin::app.response.delete-success', ['name' => 'Template']));

        return redirect()->route($this->_config['redirect']);
    }
}
