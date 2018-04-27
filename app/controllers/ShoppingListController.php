<?php

class ShoppingListController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return ShoppingList::byLoggedInUser()->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $name = Input::get('name');
        $forStore = Input::get('for_store');
        
        $validator = \ControllerHelper::validateInputsAgainst(
            array(
                'name' => 'required|string|max:256',
                'for_store' => 'string|max:128',
            ),
            array(
                'name' => $name,
                'for_store' => $forStore
            )
        );

        if ($validator->fails()) {
            return ControllerHelper::respondWithValidationErrors($validator);
        }
        
        $newShoppingList = ShoppingList::create(array(
            'user_id' => User::getLoggedInUserId(),
            'name' => $name,
            'for_store' => $forStore,
        ));

        return Response::json($newShoppingList, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $validator = \ControllerHelper::validateInputsAgainst(
            [
                'id' => 'required|integer|min:1'
            ],
            [
                'id' => $id,
            ]
        );

        if ($validator->fails()) {
            return ControllerHelper::respondWithValidationErrors($validator);
        }
        
        return ShoppingList::byLoggedInUser()->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $propertiesToUpdate = [
            'name' => Input::get('name'),
            'for_store' => Input::get('for_store'),
        ];
        
        $validator = \ControllerHelper::validateInputsAgainst(
            array(
                'name' => 'string|max:256',
                'for_store' => 'string|max:128',
                'id' => 'required|integer|min:1'
            ),
            array_merge(
                [
                    'id' => $id,
                ],
                $propertiesToUpdate
            )
        );

        if ($validator->fails()) {
            return ControllerHelper::respondWithValidationErrors($validator);
        }
        
        $shoppingList = ShoppingList::byLoggedInUser()->findOrFail($id);
        $shouldSave = False;

        foreach (array_keys($propertiesToUpdate) as $property) {
            if (isset($propertiesToUpdate[$property])) {
                $shoppingList->{$property} = $propertiesToUpdate[$property];
                $shouldSave = True;
            }
        }
        if ($shouldSave) {
            $shoppingList->save();
        }

        return Response::json($shoppingList);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $validator = \ControllerHelper::validateInputsAgainst(
            [
                'id' => 'required|integer|min:1'
            ],
            [
                'id' => $id,
            ]
        );

        if ($validator->fails()) {
            return ControllerHelper::respondWithValidationErrors($validator);
        }

        $shoppingList = ShoppingList::byLoggedInUser()->findOrFail($id);

        $shoppingList->delete();

        return Response::json('', 204);
    }

}
