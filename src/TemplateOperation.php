<?php

namespace SoufieneSlimi\TemplateOperation;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Template;

trait TemplateOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  prefix of the route name
     * @param string $controller name of the current CrudController
     */
    protected function setupTemplateRoutes($segment, $routeName, $controller)
    {
        // list all templates for this model
        Route::get($segment.'/template', [
            'as' => $routeName.'.listTemplate',
            'uses' => $controller.'@listTemplate',
            'operation' => 'template',
        ]);

        // show the form to create a template
        Route::get($segment.'/template/create', [
            'as' => $routeName.'.createTemplate',
            'uses' => $controller.'@createTemplate',
            'operation' => 'template',
        ]);

        // save the template to database
        Route::post($segment.'/template', [
            'as' => $routeName.'.storeTemplate',
            'uses' => $controller.'@storeTemplate',
            'operation' => 'template',
        ]);

        // to apply a template (use this template button)
        Route::post($segment.'/create', [
            'as' => $routeName.'.createFromTemplate',
            'uses' => $controller.'@create',
            'operation' => 'create',
            'middleware' => ApplyTemplate::class,
        ]);

        // delete a template
        Route::delete($segment.'/template/delete', [
            'as' => $routeName.'.deleteTemplate',
            'uses' => $controller.'@deleteTemplate',
            'operation' => 'template',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupTemplateDefaults()
    {
        // allow access to the operation
        $this->crud->allowAccess('template');

        $this->crud->setRoute($this->crud->getRoute().'/template');

        $this->crud->operation('template', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
            $this->crud->loadDefaultOperationSettingsFromConfig('backpack.crud.operations.create');
            $this->crud->setupDefaultSaveActions();
        });

        $this->crud->operation('list', function () {
            $this->crud->addButton('top', 'template', 'view', 'template-operation::template_button');
        });
    }

    /**
     * Display the templates for this model.
     *
     * @return \Illuminate\View\View
     */
    public function listTemplate()
    {
        $this->crud->hasAccessOrFail('template');

        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? mb_ucfirst($this->crud->entity_name).' '.trans('template-operation::template.templates');
        $this->data['templates'] = Template::whereModelFqn(get_class($this->crud->model))->get();

        return view($this->crud->get('template.listView') ?? 'template-operation::templates', $this->data);
    }

    /**
     * Display the form to create a template for specified model.
     *
     * @return \Illuminate\View\View
     */
    public function createTemplate()
    {
        // check permission
        $this->crud->hasAccessOrFail('template');

        // add the template name field
        $this->crud->addField([
            'name' => 'template_name',
            'type' => 'text',
            'label' => trans('template-operation::template.template_name'),
            'tab' => 'Template setting',
        ]);

        // update breadcrumbs
        $this->data['breadcrumbs'] = [
            trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
            $this->crud->entity_name_plural => url($this->crud->getRoute()),
            trans('template-operation::template.templates') => url($this->crud->getRoute().'/template'),
            trans('backpack::crud.add') => false,
        ];

        // update some crud data for create operation
        $this->crud->removeSaveActions(['save_and_edit', 'save_and_preview']);
        $this->crud->entity_name_plural .= ' '.mb_strtolower(trans('template-operation::template.templates'));
        $this->crud->entity_name .= ' '.mb_strtolower(trans('template-operation::template.template'));

        // prepare the fields you need to show
        $this->crud->route .= '/template';
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->crud->getSaveAction();
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.add').' '.$this->crud->entity_name;

        return view($this->crud->getCreateView(), $this->data);
    }

    /**
     * Save template in database.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeTemplate()
    {
        $this->crud->hasAccessOrFail('create');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();
        // validate template name field
        $this->crud->getRequest()->validate([
            'template_name' => 'required|min:3|max:255',
        ], [
            'template_name.required' => trans('template-operation::template.validation.template_name_required'),
            'template_name.min' => trans('template-operation::template.validation.template_name_min'),
            'template_name.max' => trans('template-operation::template.validation.template_name_max'),
        ]);

        $templateData = $this->crud->getStrippedSaveRequest();

        // don't save the excluded inputs
        foreach ($this->crud->getOperationSetting('excludedInputs') ?? [] as $excluded) {
            unset($templateData[$excluded]);
        }

        // save the template
        Template::make(
            $this->crud->getRequest()->template_name,
            $templateData,
            get_class($this->crud->getModel())
        );

        $this->crud->setSaveAction();

        return $this->crud->performSaveAction();
    }

    /**
     * Delete a template via ajax.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteTemplate()
    {
        $request =$this->crud->getRequest();

        // see if the template exists in the database
        $validator = Validator::make($request->only('template_id'), [
            'template_id' => 'required|exists:form_templates,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        Template::find($request->template_id)->delete();

        return response()->json([], 200);
    }
}
